<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['menu_id', 'nama', 'harga'])]
class OpsiTambahan extends Model
{
    protected $table = 'opsi_tambahan';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(MenuKatering::class, 'menu_id');
    }

    public function itemPesanan(): BelongsToMany
    {
        return $this->belongsToMany(
            ItemPesanan::class,
            'item_opsi_tambahan',
            'opsi_tambahan_id',
            'item_pesanan_id',
        );
    }
}
