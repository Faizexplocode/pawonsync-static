<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['pesanan_id', 'menu_id', 'paket_id', 'qty'])]
class ItemPesanan extends Model
{
    protected $table = 'item_pesanan';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(MenuKatering::class, 'menu_id');
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketMenu::class, 'paket_id');
    }

    public function opsiTambahan(): BelongsToMany
    {
        return $this->belongsToMany(
            OpsiTambahan::class,
            'item_opsi_tambahan',
            'item_pesanan_id',
            'opsi_tambahan_id',
        );
    }

    public function hargaSatuan(): float
    {
        $harga = $this->paket?->harga ?? $this->menu?->harga ?? 0;

        return (float) $harga + $this->opsiTambahan->sum('harga');
    }

    public function subtotal(): float
    {
        return $this->hargaSatuan() * $this->qty;
    }
}
