<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'rating', 'komentar', 'waktu'])]
class Ulasan extends Model
{
    protected $table = 'ulasan';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'waktu' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
