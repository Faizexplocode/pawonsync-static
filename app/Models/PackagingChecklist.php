<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'selesai', 'jumlah_box'])]
class PackagingChecklist extends Model
{
    protected $table = 'packaging_checklist';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'selesai' => 'boolean',
            'jumlah_box' => 'integer',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function persen(): int
    {
        if ($this->pesanan->jumlah_porsi <= 0) {
            return 0;
        }

        return min(100, (int) round($this->jumlah_box / $this->pesanan->jumlah_porsi * 100));
    }
}
