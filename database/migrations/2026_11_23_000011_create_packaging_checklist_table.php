<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packaging_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->cascadeOnDelete();
            $table->boolean('selesai')->default(false);
            $table->integer('jumlah_box')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packaging_checklist');
    }
};
