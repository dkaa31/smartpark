<?php

namespace Database\Seeders;

use App\Models\TbKendaraan;
use Illuminate\Database\Seeder;

class TbKendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $kendaraan = [
            ['plat_nomor' => 'B 1234 ABC', 'jenis_kendaraan' => 'motor',  'warna' => 'Merah',  'pemilik' => 'Andi Wijaya',    'id_user' => null],
            ['plat_nomor' => 'B 5678 DEF', 'jenis_kendaraan' => 'mobil',  'warna' => 'Putih',  'pemilik' => 'Dewi Lestari',   'id_user' => null],
            ['plat_nomor' => 'D 9012 GHI', 'jenis_kendaraan' => 'motor',  'warna' => 'Hitam',  'pemilik' => 'Rudi Hartono',   'id_user' => null],
            ['plat_nomor' => 'F 3456 JKL', 'jenis_kendaraan' => 'mobil',  'warna' => 'Silver', 'pemilik' => 'Maya Sari',      'id_user' => null],
            ['plat_nomor' => 'B 7890 MNO', 'jenis_kendaraan' => 'motor',  'warna' => 'Biru',   'pemilik' => 'Hendra Gunawan', 'id_user' => null],
            ['plat_nomor' => 'T 1111 PQR', 'jenis_kendaraan' => 'lainnya','warna' => 'Kuning', 'pemilik' => 'Pak Joko',       'id_user' => null],
        ];

        foreach ($kendaraan as $k) {
            TbKendaraan::create($k);
        }
    }
}
