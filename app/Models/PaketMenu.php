<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['menu_id', 'nama', 'harga'])]
class PaketMenu extends Model
{
    protected $table = 'paket_menu';

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

    public function itemPesanan(): HasMany
    {
        return $this->hasMany(ItemPesanan::class, 'paket_id');
    }
}
