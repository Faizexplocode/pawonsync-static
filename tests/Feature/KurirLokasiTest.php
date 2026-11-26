<?php

namespace Tests\Feature;

use App\Models\Pengiriman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KurirLokasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_kurir_mengirim_lokasi_gps_lewat_json(): void
    {
        $kurir = User::where('email', 'kurir@pawonsync.test')->firstOrFail();
        $pengiriman = Pengiriman::where('kurir_id', $kurir->id)->firstOrFail();

        $this->actingAs($kurir)
            ->postJson(route('kurir.live.lokasi', $pengiriman), [
                'latitude' => -7.2820,
                'longitude' => 112.7950,
            ])
            ->assertOk()
            ->assertJson(['latitude' => -7.282, 'longitude' => 112.795]);

        $this->assertTrue(
            $pengiriman->lokasiKurir()->where('latitude', -7.282)->where('longitude', 112.795)->exists(),
        );
    }

    public function test_lokasi_tidak_valid_ditolak(): void
    {
        $kurir = User::where('email', 'kurir@pawonsync.test')->firstOrFail();
        $pengiriman = Pengiriman::where('kurir_id', $kurir->id)->firstOrFail();

        $this->actingAs($kurir)
            ->postJson(route('kurir.live.lokasi', $pengiriman), ['latitude' => 200, 'longitude' => 112.79])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('latitude');
    }

    public function test_kurir_lain_tidak_boleh_mengirim_lokasi(): void
    {
        $kurir = User::where('email', 'kurir@pawonsync.test')->firstOrFail();
        $pengiriman = Pengiriman::where('kurir_id', $kurir->id)->firstOrFail();
        $kurirLain = User::factory()->create(['role' => 'kurir']);

        $this->actingAs($kurirLain)
            ->postJson(route('kurir.live.lokasi', $pengiriman), ['latitude' => -7.28, 'longitude' => 112.79])
            ->assertForbidden();
    }
}
