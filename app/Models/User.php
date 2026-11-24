<?php

namespace App\Models;

use App\Enums\StatusPesanan;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['nama', 'email', 'password', 'role'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function menuKatering(): HasMany
    {
        return $this->hasMany(MenuKatering::class, 'seller_id');
    }

    public function kalenderKapasitas(): HasMany
    {
        return $this->hasMany(KalenderKapasitas::class, 'seller_id');
    }

    public function pesananSebagaiBuyer(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'buyer_id');
    }

    public function pesananSebagaiSeller(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'seller_id');
    }

    public function pengirimanSebagaiKurir(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'kurir_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isKurir(): bool
    {
        return $this->role === 'kurir';
    }

    public function homeRoute(): string
    {
        return match ($this->role) {
            'seller' => 'seller.dashboard',
            'kurir' => 'kurir.beranda',
            default => 'buyer.beranda',
        };
    }

    public function pesananAktif(): ?Pesanan
    {
        return $this->pesananSebagaiBuyer()
            ->whereNotIn('status', [StatusPesanan::Selesai->value, StatusPesanan::Dibatalkan->value])
            ->orderBy('tanggal_acara')
            ->first();
    }
}
