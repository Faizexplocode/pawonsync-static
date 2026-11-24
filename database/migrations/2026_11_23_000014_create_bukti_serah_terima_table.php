<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_serah_terima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_id')->unique()->constrained('pengiriman')->cascadeOnDelete();
            $table->string('foto_url', 255);
            $table->timestamp('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_serah_terima');
    }
};
