<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TbTransaksi;
use Carbon\Carbon;

class DashboardOwnerController extends Controller
{
    public function index()
    {
        $today         = today();
        $pendapatanHari = TbTransaksi::where('status', 'keluar')
            ->whereDate('waktu_keluar', $today)
            ->sum('biaya_total');

        $kendaraanHari = TbTransaksi::whereDate('waktu_masuk', $today)->count();

        $pendapatanBulan = TbTransaksi::where('status', 'keluar')
            ->whereMonth('waktu_keluar', $today->month)
            ->whereYear('waktu_keluar', $today->year)
            ->sum('biaya_total');

        // Rekap 7 hari terakhir
        $rekap7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $rekap7Hari[] = [
                'tanggal'   => $date->format('d M'),
                'kendaraan' => TbTransaksi::whereDate('waktu_masuk', $date)->count(),
                'pendapatan'=> TbTransaksi::where('status', 'keluar')->whereDate('waktu_keluar', $date)->sum('biaya_total'),
            ];
        }

        return view('owner.dashboard', compact('pendapatanHari', 'kendaraanHari', 'pendapatanBulan', 'rekap7Hari'));
    }
}
