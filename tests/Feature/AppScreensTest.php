<?php

namespace Tests\Feature;

use App\Models\MenuKatering;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppScreensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_splash_from_protected_pages(): void
    {
        $this->get(route('buyer.beranda'))->assertRedirect(route('splash'));
    }

    public function test_buyer_can_login_and_open_all_screens(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();

        $this->post(route('login.store', 'buyer'), [
            'email' => $buyer->email,
            'password' => 'password',
        ])->assertRedirect(route('buyer.beranda'));

        $this->actingAs($buyer);

        $menu = MenuKatering::firstOrFail();
        $order = $buyer->pesananSebagaiBuyer()->where('status', 'diproses')->firstOrFail();
        $selesai = $buyer->pesananSebagaiBuyer()->where('status', 'selesai')->firstOrFail();

        $pages = [
            route('buyer.beranda'),
            route('buyer.katalog'),
            route('buyer.filter'),
            route('buyer.menu', $menu),
            route('buyer.keranjang'),
            route('buyer.pesanan'),
            route('buyer.pesanan.detail', $order),
            route('buyer.tracking', $order),
            route('buyer.tiba', $order),
            route('buyer.notifikasi'),
            route('buyer.profil'),
            route('buyer.pesanan.detail', $selesai),
        ];

        foreach ($pages as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_seller_can_open_all_screens(): void
    {
        $seller = User::where('email', 'nusa@pawonsync.test')->firstOrFail();
        $this->actingAs($seller);

        $diproses = Pesanan::where('seller_id', $seller->id)->where('status', 'siap_kirim')->firstOrFail();

        $pages = [
            route('seller.dashboard'),
            route('seller.pesanan'),
            route('seller.pesanan', ['tab' => 'siap']),
            route('seller.pesanan.detail', $diproses),
            route('seller.milestone', $diproses),
            route('seller.packaging', $diproses),
            route('seller.kurir', $diproses),
            route('seller.katalog'),
            route('seller.menu'),
            route('seller.menu', MenuKatering::where('seller_id', $seller->id)->firstOrFail()),
            route('seller.kalkulasi'),
            route('seller.kalender'),
            route('seller.keuangan'),
            route('seller.promosi'),
            route('seller.pengaturan'),
            route('seller.retur'),
        ];

        foreach ($pages as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_kurir_can_open_all_screens(): void
    {
        $kurir = User::where('email', 'kurir@pawonsync.test')->firstOrFail();
        $this->actingAs($kurir);

        $pengiriman = Pengiriman::where('kurir_id', $kurir->id)->firstOrFail();

        $pages = [
            route('kurir.beranda'),
            route('kurir.tugas'),
            route('kurir.tugas', ['tab' => 'selesai']),
            route('kurir.tugas.detail', $pengiriman),
            route('kurir.departure', $pengiriman),
            route('kurir.live', $pengiriman),
            route('kurir.bukti', $pengiriman),
        ];

        foreach ($pages as $url) {
            $this->get($url)->assertOk();
        }
    }
}
