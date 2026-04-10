<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TbAreaParkir;
use App\Models\TbTransaksi;
use App\Models\TbTarif;
use App\Models\TbKendaraan;

class DashboardPetugasController extends Controller
{
    public function index()
    {
        $areas        = TbAreaParkir::all();
        $tarif        = TbTarif::all();
        $totalShift   = TbTransaksi::whereDate('waktu_masuk', today())->count();
        $totalKapasitas = TbAreaParkir::sum('kapasitas');
        $totalTerisi    = TbAreaParkir::sum('terisi');
        $persentase     = $totalKapasitas > 0 ? round(($totalTerisi / $totalKapasitas) * 100, 1) : 0;

        return view('petugas.dashboard', compact('areas', 'tarif', 'totalShift', 'persentase'));
    }
}
