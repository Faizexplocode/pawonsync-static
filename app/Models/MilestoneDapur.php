<?php

namespace App\Models;

use App\Enums\StatusMilestone;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pesanan_id', 'status', 'foto_url', 'waktu'])]
class MilestoneDapur extends Model
{
    protected $table = 'milestone_dapur';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => StatusMilestone::class,
            'waktu' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
