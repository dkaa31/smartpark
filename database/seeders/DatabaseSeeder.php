<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TbUserSeeder::class,
            TbTarifSeeder::class,
            TbAreaParkirSeeder::class,
            TbKendaraanSeeder::class,
            TbTransaksiSeeder::class,
        ]);
    }
}
