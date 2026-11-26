<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BerandaBuyerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_beranda_menampilkan_bagian_sesuai_desain_figma(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();

        $this->actingAs($buyer)
            ->get(route('buyer.beranda'))
            ->assertOk()
            ->assertSee('Halo, '.$buyer->nama)
            ->assertSee('Kirim ke:')
            ->assertSee('notif')
            ->assertSee(config('katalog.promo.badge'))
            ->assertSee('Kategori')
            ->assertSee('Pesanan Aktif')
            ->assertSee('Rekomendasi untuk Anda')
            ->assertSee('Flash Sale Hari Ini')
            ->assertSee(config('katalog.flash_sale.title'))
            ->assertSee('Berakhir');
    }

    public function test_kategori_di_beranda_menautkan_ke_katalog_yang_terfilter(): void
    {
        $buyer = User::where('email', 'buyer@pawonsync.test')->firstOrFail();
        $tautanTumpeng = route('buyer.katalog', ['q' => 'Tumpeng']);

        $beranda = $this->actingAs($buyer)->get(route('buyer.beranda'));
        $katalog = $this->get($tautanTumpeng);

        $beranda->assertOk()->assertSee('href="'.$tautanTumpeng.'"', false);
        $katalog->assertOk()
            ->assertSee('Tumpeng Mini Syukuran')
            ->assertDontSee('Nasi Box Ayam Geprek');
    }
}
