<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('tb_user')->cascadeOnDelete();
            $table->text('aktivitas');
            $table->dateTime('waktu_aktivitas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_log_aktivitas');
    }
};
