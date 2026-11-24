<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['menu_id', 'foto', 'gramasi', 'deskripsi'])]
class DummyPlate extends Model
{
    protected $table = 'dummy_plate';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'gramasi' => 'decimal:2',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(MenuKatering::class, 'menu_id');
    }
}
