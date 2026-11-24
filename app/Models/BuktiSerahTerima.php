<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pengiriman_id', 'foto_url', 'waktu'])]
class BuktiSerahTerima extends Model
{
    protected $table = 'bukti_serah_terima';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
        ];
    }

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id');
    }
}
