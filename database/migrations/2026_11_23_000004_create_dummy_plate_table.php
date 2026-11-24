<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dummy_plate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->unique()->constrained('menu_katering')->cascadeOnDelete();
            $table->string('foto', 255)->nullable();
            $table->decimal('gramasi', 10, 2)->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dummy_plate');
    }
};
