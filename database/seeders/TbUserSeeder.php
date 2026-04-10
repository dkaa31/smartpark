<?php

namespace Database\Seeders;

use App\Models\TbUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TbUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['nama_lengkap' => 'Administrator', 'username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'admin', 'status_aktif' => 'aktif'],
            ['nama_lengkap' => 'Petugas Parkir', 'username' => 'petugas', 'password' => Hash::make('petugas123'), 'role' => 'petugas', 'status_aktif' => 'aktif'],
            ['nama_lengkap' => 'Owner SmartPark', 'username' => 'owner', 'password' => Hash::make('owner123'), 'role' => 'owner', 'status_aktif' => 'aktif'],
            ['nama_lengkap' => 'Budi Santoso', 'username' => 'budi', 'password' => Hash::make('password'), 'role' => 'petugas', 'status_aktif' => 'aktif'],
            ['nama_lengkap' => 'Siti Rahayu', 'username' => 'siti', 'password' => Hash::make('password'), 'role' => 'petugas', 'status_aktif' => 'nonaktif'],
        ];

        foreach ($users as $user) {
            TbUser::create($user);
        }
    }
}
