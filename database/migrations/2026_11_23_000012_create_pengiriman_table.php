<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('kurir_id')->constrained('users');
            $table->string('status', 30);
            $table->integer('eta_menit')->nullable();
            $table->timestamp('waktu_berangkat')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
