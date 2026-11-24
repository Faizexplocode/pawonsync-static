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
}
