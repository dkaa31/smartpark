<?php

namespace Database\Seeders;

use App\Models\TbAreaParkir;
use Illuminate\Database\Seeder;

class TbAreaParkirSeeder extends Seeder
{
    public function run(): void
    {
        TbAreaParkir::insert([
            ['nama_area' => 'Area A - Lantai 1', 'kapasitas' => 50, 'terisi' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['nama_area' => 'Area B - Lantai 2', 'kapasitas' => 40, 'terisi' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['nama_area' => 'Area C - Basement', 'kapasitas' => 30, 'terisi' => 5,  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
