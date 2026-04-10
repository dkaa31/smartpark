<?php

namespace Database\Seeders;

use App\Models\TbTransaksi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TbTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        $now = Carbon::now();

        // Transaksi selesai (keluar) untuk 7 hari terakhir
        for ($day = 6; $day >= 1; $day--) {
            $date = $now->copy()->subDays($day);
            $count = rand(8, 15);
            for ($i = 0; $i < $count; $i++) {
                $masuk = $date->copy()->setHour(rand(7, 16))->setMinute(rand(0, 59));
                $durasi = rand(1, 8);
                $keluar = $masuk->copy()->addHours($durasi);
                $idKendaraan = rand(1, 6);
                $idTarif = $idKendaraan <= 3 ? 1 : ($idKendaraan == 6 ? 3 : 2);
                $tarifPerJam = $idTarif == 1 ? 2000 : ($idTarif == 3 ? 3000 : 5000);
                $biaya = $durasi * $tarifPerJam;

                $data[] = [
                    'id_kendaraan' => $idKendaraan,
                    'waktu_masuk'  => $masuk,
                    'waktu_keluar' => $keluar,
                    'id_tarif'     => $idTarif,
                    'durasi_jam'   => $durasi,
                    'biaya_total'  => $biaya,
                    'status'       => 'keluar',
                    'id_user'      => 2,
                    'id_area'      => rand(1, 3),
                    'created_at'   => $masuk,
                    'updated_at'   => $keluar,
                ];
            }
        }

        // Beberapa transaksi masuk hari ini (belum keluar)
        for ($i = 0; $i < 5; $i++) {
            $masuk = $now->copy()->subHours(rand(1, 4));
            $idKendaraan = rand(1, 6);
            $idTarif = $idKendaraan <= 3 ? 1 : ($idKendaraan == 6 ? 3 : 2);

            $data[] = [
                'id_kendaraan' => $idKendaraan,
                'waktu_masuk'  => $masuk,
                'waktu_keluar' => null,
                'id_tarif'     => $idTarif,
                'durasi_jam'   => null,
                'biaya_total'  => null,
                'status'       => 'masuk',
                'id_user'      => 2,
                'id_area'      => rand(1, 3),
                'created_at'   => $masuk,
                'updated_at'   => $masuk,
            ];
        }

        TbTransaksi::insert($data);
    }
}
