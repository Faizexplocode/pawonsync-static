<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestone_dapur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('status', 50);
            $table->string('foto_url', 255)->nullable();
            $table->timestamp('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestone_dapur');
    }
};
