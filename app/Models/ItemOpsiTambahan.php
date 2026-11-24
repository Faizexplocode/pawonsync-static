<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['item_pesanan_id', 'opsi_tambahan_id'])]
class ItemOpsiTambahan extends Model
{
    protected $table = 'item_opsi_tambahan';

    public $incrementing = false;

    public $timestamps = false;
}
