<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('plat_nomor')->unique();
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'lainnya']);
            $table->string('warna');
            $table->string('pemilik');
            $table->foreignId('id_user')->nullable()->constrained('tb_user')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kendaraan');
    }
};
