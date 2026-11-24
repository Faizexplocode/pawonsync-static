<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'pesanan_id', 'pesan', 'dibaca', 'waktu'])]
class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'dibaca' => 'boolean',
            'waktu' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
