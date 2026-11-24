<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['seller_id', 'tanggal', 'kapasitas_maks', 'terpakai'])]
class KalenderKapasitas extends Model
{
    protected $table = 'kalender_kapasitas';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'kapasitas_maks' => 'integer',
            'terpakai' => 'integer',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function sisa(): int
    {
        return max(0, $this->kapasitas_maks - $this->terpakai);
    }

    public function persenTerpakai(): int
    {
        if ($this->kapasitas_maks <= 0) {
            return 0;
        }

        return (int) round($this->terpakai / $this->kapasitas_maks * 100);
    }
}
