<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu_katering')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->decimal('harga', 12, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_menu');
    }
};
