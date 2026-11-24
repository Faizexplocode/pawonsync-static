<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['seller_id', 'nama', 'deskripsi', 'harga', 'foto'])]
class MenuKatering extends Model
{
    protected $table = 'menu_katering';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function dummyPlate(): HasOne
    {
        return $this->hasOne(DummyPlate::class, 'menu_id');
    }

    public function paketMenu(): HasMany
    {
        return $this->hasMany(PaketMenu::class, 'menu_id');
    }

    public function opsiTambahan(): HasMany
    {
        return $this->hasMany(OpsiTambahan::class, 'menu_id');
    }

    public function itemPesanan(): HasMany
    {
        return $this->hasMany(ItemPesanan::class, 'menu_id');
    }
}
