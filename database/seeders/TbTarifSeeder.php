<?php

namespace Database\Seeders;

use App\Models\TbTarif;
use Illuminate\Database\Seeder;

class TbTarifSeeder extends Seeder
{
    public function run(): void
    {
        TbTarif::insert([
            ['jenis_kendaraan' => 'motor',  'tarif_per_jam' => 2000,  'created_at' => now(), 'updated_at' => now()],
            ['jenis_kendaraan' => 'mobil',  'tarif_per_jam' => 5000,  'created_at' => now(), 'updated_at' => now()],
            ['jenis_kendaraan' => 'lainnya','tarif_per_jam' => 3000,  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
