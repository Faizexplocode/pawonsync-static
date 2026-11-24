<?php

namespace App\Models;

use App\Enums\StatusPesanan;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'buyer_id',
    'seller_id',
    'tanggal_acara',
    'waktu_acara',
    'alamat_venue',
    'jumlah_porsi',
    'total_harga',
    'status',
])]
class Pesanan extends Model
{
    protected $table = 'pesanan';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal_acara' => 'date',
            'jumlah_porsi' => 'integer',
            'total_harga' => 'decimal:2',
            'status' => StatusPesanan::class,
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function itemPesanan(): HasMany
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    public function milestoneDapur(): HasMany
    {
        return $this->hasMany(MilestoneDapur::class, 'pesanan_id')->orderBy('waktu');
    }

    public function packagingChecklist(): HasOne
    {
        return $this->hasOne(PackagingChecklist::class, 'pesanan_id');
    }

    public function pengiriman(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'pesanan_id');
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class, 'pesanan_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'pesanan_id');
    }

    public function kode(): string
    {
        return 'PWS-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function judul(): string
    {
        $venue = trim((string) $this->alamat_venue);

        return str($venue)->before(',')->limit(38)->toString() ?: 'Pesanan '.$this->kode();
    }

    public function progress(): int
    {
        return $this->status->progress();
    }

    public function selesaiDibatalkan(): bool
    {
        return in_array($this->status, [StatusPesanan::Selesai, StatusPesanan::Dibatalkan], true);
    }
}
