<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menu_katering');
            $table->foreignId('paket_id')->nullable()->constrained('paket_menu');
            $table->integer('qty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_pesanan');
    }
};
