<?php

namespace Tests\Feature;

use App\Enums\StatusPembayaran;
use App\Enums\StatusPengiriman;
use App\Enums\StatusPesanan;
use App\Models\BuktiSerahTerima;
use App\Models\LokasiKurir;
use App\Models\MenuKatering;
use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlurPesananTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Storage::fake('public');
    }

    public function test_alur_lengkap_dari_keranjang_sampai_ulasan(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();
        $seller = User::where('email', 'seller@pawonsync.test')->firstOrFail();
        $kurir = User::where('email', 'kurir@pawonsync.test')->firstOrFail();
        $menu = MenuKatering::where('seller_id', $seller->id)->firstOrFail();
        $paket = $menu->paketMenu()->first();

        $this->actingAs($buyer);

        $this->post(route('buyer.keranjang.tambah'), [
            'menu_id' => $menu->id,
            'paket_id' => $paket?->id,
            'qty' => 50,
        ])->assertRedirect(route('buyer.keranjang'));

        $this->get(route('buyer.keranjang'))->assertOk()->assertSee($menu->nama);

        $this->get(route('buyer.checkout'))->assertOk();

        $this->post(route('buyer.checkout.store'), [
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'waktu_acara' => '11:00',
            'alamat_venue' => 'Graha ITS Sukolilo, Surabaya',
        ])->assertRedirect();

        $pesanan = Pesanan::where('buyer_id', $buyer->id)->latest('id')->firstOrFail();
        $this->assertSame(StatusPesanan::MenungguPembayaran, $pesanan->status);
        $this->assertSame(50, $pesanan->jumlah_porsi);
        $this->assertSame(1, $pesanan->itemPesanan()->count());

        $this->get(route('buyer.pembayaran', $pesanan))->assertOk();

        $this->post(route('buyer.pembayaran.bayar', $pesanan), ['metode' => 'qris'])
            ->assertRedirect(route('buyer.konfirmasi', $pesanan));

        $pesanan->refresh();
        $this->assertSame(StatusPesanan::Dibayar, $pesanan->status);
        $this->assertSame(StatusPembayaran::Berhasil, $pesanan->pembayaran()->firstOrFail()->status);

        $this->get(route('buyer.konfirmasi', $pesanan))->assertOk();

        $this->actingAs($seller);

        $this->post(route('seller.pesanan.konfirmasi', $pesanan))
            ->assertRedirect(route('seller.pesanan.detail', $pesanan));
        $this->assertSame(StatusPesanan::Dikonfirmasi, $pesanan->refresh()->status);

        $this->post(route('seller.milestone.store', $pesanan), ['status' => 'memasak'])
            ->assertRedirect(route('seller.milestone', $pesanan));
        $this->assertSame(StatusPesanan::Diproses, $pesanan->refresh()->status);

        $this->post(route('seller.packaging.store', $pesanan), [
            'jumlah_box' => 50,
            'selesai' => '1',
        ])->assertRedirect(route('seller.kurir', $pesanan));

        $pesanan->refresh();
        $this->assertSame(StatusPesanan::SiapKirim, $pesanan->status);
        $this->assertTrue((bool) $pesanan->packagingChecklist()->firstOrFail()->selesai);

        $this->get(route('seller.kurir', $pesanan))->assertOk();

        $this->post(route('seller.kurir.tugaskan', $pesanan), [
            'kurir_id' => $kurir->id,
            'eta_menit' => 30,
        ])->assertRedirect(route('seller.pesanan.detail', $pesanan));

        $pengiriman = $pesanan->pengiriman()->firstOrFail();
        $this->assertSame($kurir->id, $pengiriman->kurir_id);
        $this->assertSame(StatusPengiriman::Ditugaskan, $pengiriman->status);

        $this->actingAs($kurir);

        $this->get(route('kurir.beranda'))->assertOk();

        $this->post(route('kurir.departure.mulai', $pengiriman))
            ->assertRedirect(route('kurir.live', $pengiriman));

        $pengiriman->refresh();
        $this->assertSame(StatusPengiriman::Berangkat, $pengiriman->status);
        $this->assertSame(StatusPesanan::Dikirim, $pesanan->refresh()->status);

        $this->post(route('kurir.live.lokasi', $pengiriman), [
            'latitude' => -7.2819,
            'longitude' => 112.7952,
        ])->assertRedirect();

        $this->assertSame(1, LokasiKurir::where('pengiriman_id', $pengiriman->id)->count());

        $this->post(route('kurir.bukti.store', $pengiriman), [
            'foto' => UploadedFile::fake()->image('bukti.jpg'),
            'penerima' => 'Staf Graha ITS',
            'jumlah_box' => 50,
        ])->assertRedirect(route('kurir.tugas'));

        $pengiriman->refresh();
        $this->assertSame(StatusPengiriman::Tiba, $pengiriman->status);
        $this->assertSame(1, BuktiSerahTerima::where('pengiriman_id', $pengiriman->id)->count());

        $this->actingAs($buyer);

        $this->get(route('buyer.tiba', $pesanan))->assertOk();

        $this->post(route('buyer.tiba.konfirmasi', $pesanan))
            ->assertRedirect(route('buyer.pesanan.detail', $pesanan));
        $this->assertSame(StatusPesanan::Selesai, $pesanan->refresh()->status);

        $this->get(route('buyer.ulasan', $pesanan))->assertOk();

        $this->post(route('buyer.ulasan.store', $pesanan), [
            'rating' => 5,
            'komentar' => 'Pesanan tepat waktu dan rasanya enak.',
        ])->assertRedirect(route('buyer.pesanan.detail', $pesanan));

        $ulasan = Ulasan::where('pesanan_id', $pesanan->id)->firstOrFail();
        $this->assertSame(5, $ulasan->rating);

        $this->post(route('buyer.notifikasi.dibaca'))->assertRedirect();

        $this->assertSame(
            0,
            $buyer->notifikasi()->where('dibaca', false)->count(),
        );
    }

    public function test_buyer_lain_tidak_boleh_mengakses_pesanan_orang(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();
        $lain = User::where('role', 'buyer')->where('id', '!=', $buyer->id)->firstOrFail();
        $pesanan = Pesanan::where('buyer_id', $buyer->id)->firstOrFail();

        $this->actingAs($lain)
            ->get(route('buyer.pesanan.detail', $pesanan))
            ->assertForbidden();
    }

    public function test_seller_tidak_boleh_mengubah_menu_seller_lain(): void
    {
        $seller = User::where('email', 'seller@pawonsync.test')->firstOrFail();
        $lain = User::where('role', 'seller')->where('id', '!=', $seller->id)->firstOrFail();
        $menu = MenuKatering::where('seller_id', $lain->id)->firstOrFail();

        $this->actingAs($seller)
            ->get(route('seller.menu', $menu))
            ->assertForbidden();
    }

    public function test_admin_cannot_login_as_buyer_with_wrong_password(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();

        $this->post(route('login.store', 'buyer'), [
            'email' => $buyer->email,
            'password' => 'salah-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
