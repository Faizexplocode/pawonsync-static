<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->cascadeOnDelete();
            $table->integer('rating');
            $table->text('komentar')->nullable();
            $table->timestamp('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
