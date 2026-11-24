<?php

namespace App\Models;

use App\Enums\StatusPembayaran;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'jumlah', 'metode', 'status', 'waktu_bayar'])]
class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'status' => StatusPembayaran::class,
            'waktu_bayar' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function metodeLabel(): string
    {
        return match ($this->metode) {
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'cod' => 'Bayar di Tempat',
            default => str($this->metode)->headline()->toString(),
        };
    }
}
