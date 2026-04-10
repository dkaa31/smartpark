<?php

namespace App\Helpers;

use App\Models\TbTarif;

class BiayaHelper
{
    /**
     * Konstanta: cap harian = berapa kali lipat tarif per jam.
     * Default 12 → artinya maksimal bayar setara 12 jam per hari.
     * Misal motor Rp 2.000/jam → cap = Rp 24.000/hari.
     * Misal mobil Rp 5.000/jam → cap = Rp 60.000/hari.
     */
    const CAP_JAM = 12;

    /**
     * Hitung biaya parkir dengan cap harian.
     *
     * Logika:
     * - 1 "hari parkir" = 24 jam
     * - Biaya per hari tidak boleh melebihi (tarif_per_jam × CAP_JAM)
     * - Untuk sisa jam di hari terakhir, dihitung normal tapi tidak melebihi cap
     *
     * Contoh motor Rp 2.000/jam, cap = 12 jam = Rp 24.000/hari:
     *   - 3 jam  → 3 × 2.000 = Rp 6.000
     *   - 12 jam → 12 × 2.000 = Rp 24.000 (sudah cap)
     *   - 15 jam → cap 1 hari = Rp 24.000 (tidak naik lagi)
     *   - 25 jam → 1 hari penuh (Rp 24.000) + 1 jam (Rp 2.000) = Rp 26.000
     *   - 36 jam → 1 hari penuh (Rp 24.000) + 12 jam (Rp 24.000) = Rp 48.000
     *
     * @param  int|float  $durasiJam   Durasi parkir dalam jam (sudah dibulatkan ke atas)
     * @param  float      $tarifPerJam Tarif per jam dari tb_tarif
     * @return float      Total biaya
     */
    public static function hitungBiaya(float $durasiJam, float $tarifPerJam): float
    {
        $capHarian   = $tarifPerJam * self::CAP_JAM;
        $jamPerHari  = 24;

        // Berapa hari penuh
        $hariPenuh   = (int) floor($durasiJam / $jamPerHari);
        // Sisa jam setelah hari penuh
        $sisaJam     = $durasiJam % $jamPerHari;

        // Biaya hari penuh (masing-masing kena cap)
        $biayaHariPenuh = $hariPenuh * $capHarian;

        // Biaya sisa jam (tidak boleh melebihi cap harian)
        $biayaSisa = min($sisaJam * $tarifPerJam, $capHarian);

        return $biayaHariPenuh + $biayaSisa;
    }

    /**
     * Hitung durasi dalam jam (dibulatkan ke atas, minimum 1 jam)
     */
    public static function hitungDurasi(\Carbon\Carbon $masuk, \Carbon\Carbon $keluar): int
    {
        return max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));
    }

    /**
     * Return cap harian dalam rupiah untuk ditampilkan di UI
     */
    public static function capHarian(float $tarifPerJam): float
    {
        return $tarifPerJam * self::CAP_JAM;
    }
}
