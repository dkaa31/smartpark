<?php

namespace App\Helpers;

class BiayaHelper
{
    /**
     * Hitung biaya parkir progresif dengan cap harian.
     *
     * Logika per 24 jam:
     *   - Jam ke-1          : tarif_jam1
     *   - Jam ke-2 dst      : tarif_lanjut per jam
     *   - Total tidak boleh melebihi maks_harian
     *
     * Jika durasi > 24 jam:
     *   - Setiap 24 jam dihitung sebagai 1 hari penuh (= maks_harian)
     *   - Sisa jam dihitung ulang dengan tarif progresif (tetap dibatasi maks_harian)
     *
     * Untuk jenis 'lainnya' (tidak ada di config):
     *   - Fallback: tarif_per_jam × jam, cap = tarif_per_jam × 12
     *
     * @param  int    $durasiJam    Durasi parkir dalam jam (min 1)
     * @param  string $jenisKendaraan
     * @param  float  $tarifPerJam  Dari tb_tarif (dipakai untuk fallback)
     * @return float  Total biaya
     */
    public static function hitungBiaya(int $durasiJam, string $jenisKendaraan, float $tarifPerJam): float
    {
        $config = config('tarif_parkir.' . $jenisKendaraan);

        // Fallback untuk 'lainnya' atau jenis tidak dikenal
        if (!$config) {
            $cap   = $tarifPerJam * 12;
            $biaya = $durasiJam * $tarifPerJam;
            // Hitung per hari dengan cap
            $hariPenuh  = (int) floor($durasiJam / 24);
            $sisaJam    = $durasiJam % 24;
            $biayaSisa  = min($sisaJam * $tarifPerJam, $cap);
            return ($hariPenuh * $cap) + $biayaSisa;
        }

        $tarif1    = (float) $config['tarif_jam1'];
        $tarifNext = (float) $config['tarif_lanjut'];
        $maksHari  = (float) $config['maks_harian'];

        // Hitung biaya untuk N jam (dalam 1 hari, max 24 jam)
        $hitungSatuPeriode = function (int $jam) use ($tarif1, $tarifNext, $maksHari): float {
            if ($jam <= 0) return 0;
            $biaya = $tarif1 + max(0, $jam - 1) * $tarifNext;
            return min($biaya, $maksHari);
        };

        $hariPenuh = (int) floor($durasiJam / 24);
        $sisaJam   = $durasiJam % 24;

        $total = ($hariPenuh * $maksHari) + $hitungSatuPeriode($sisaJam);

        return $total;
    }

    /**
     * Hitung durasi dalam jam (dibulatkan ke atas, minimum 1 jam)
     */
    public static function hitungDurasi(\Carbon\Carbon $masuk, \Carbon\Carbon $keluar): int
    {
        return max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));
    }

    /**
     * Ambil batas maksimal harian untuk ditampilkan di UI
     */
    public static function getMaksHarian(string $jenisKendaraan, float $tarifPerJam): float
    {
        $config = config('tarif_parkir.' . $jenisKendaraan);
        return $config ? (float) $config['maks_harian'] : $tarifPerJam * 12;
    }

    /**
     * Ambil info tarif lengkap untuk ditampilkan di UI
     */
    public static function getInfoTarif(string $jenisKendaraan, float $tarifPerJam): array
    {
        $config = config('tarif_parkir.' . $jenisKendaraan);
        if ($config) {
            return [
                'jam1'       => $config['tarif_jam1'],
                'lanjut'     => $config['tarif_lanjut'],
                'maks_harian'=> $config['maks_harian'],
                'progresif'  => true,
            ];
        }
        return [
            'jam1'       => $tarifPerJam,
            'lanjut'     => $tarifPerJam,
            'maks_harian'=> $tarifPerJam * 12,
            'progresif'  => false,
        ];
    }
}
