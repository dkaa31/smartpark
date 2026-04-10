<?php

/**
 * Konfigurasi tarif parkir progresif.
 *
 * tarif_jam1   : biaya jam pertama
 * tarif_lanjut : biaya per jam setelah jam pertama
 * maks_harian  : batas maksimal biaya per 24 jam
 *
 * Kolom tarif_per_jam di tb_tarif tetap dipakai sebagai fallback
 * untuk jenis 'lainnya' yang tidak ada di sini.
 */

return [
    'motor' => [
        'tarif_jam1'   => 2000,
        'tarif_lanjut' => 1000,
        'maks_harian'  => 10000,
    ],
    'mobil' => [
        'tarif_jam1'   => 5000,
        'tarif_lanjut' => 2000,
        'maks_harian'  => 25000,
    ],
    // lainnya: fallback ke tarif_per_jam × jam, cap = tarif_per_jam × 12
];
