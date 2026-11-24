<?php

namespace App\Models;

use App\Enums\StatusPengiriman;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['pesanan_id', 'kurir_id', 'status', 'eta_menit', 'waktu_berangkat'])]
class Pengiriman extends Model
{
    protected $table = 'pengiriman';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => StatusPengiriman::class,
            'eta_menit' => 'integer',
            'waktu_berangkat' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function kurir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }

    public function lokasiKurir(): HasMany
    {
        return $this->hasMany(LokasiKurir::class, 'pengiriman_id')->orderBy('waktu');
    }

    public function buktiSerahTerima(): HasOne
    {
        return $this->hasOne(BuktiSerahTerima::class, 'pengiriman_id');
    }

    public function lokasiTerakhir(): ?LokasiKurir
    {
        return $this->lokasiKurir()->latest('waktu')->first();
    }
}
