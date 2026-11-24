<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamp('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
