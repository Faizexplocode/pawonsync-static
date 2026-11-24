<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_opsi_tambahan', function (Blueprint $table) {
            $table->foreignId('item_pesanan_id')->constrained('item_pesanan')->cascadeOnDelete();
            $table->foreignId('opsi_tambahan_id')->constrained('opsi_tambahan')->cascadeOnDelete();

            $table->primary(['item_pesanan_id', 'opsi_tambahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_opsi_tambahan');
    }
};
