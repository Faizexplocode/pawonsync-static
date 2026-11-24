<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->date('tanggal_acara');
            $table->time('waktu_acara');
            $table->text('alamat_venue');
            $table->integer('jumlah_porsi');
            $table->decimal('total_harga', 12, 2);
            $table->string('status', 30);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
