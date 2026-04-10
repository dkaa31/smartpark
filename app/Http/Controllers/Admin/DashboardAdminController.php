<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbUser;
use App\Models\TbKendaraan;
use App\Models\TbAreaParkir;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalUser      = TbUser::count();
        $totalKendaraan = TbKendaraan::count();
        $totalArea      = TbAreaParkir::count();
        $userTerbaru    = TbUser::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUser', 'totalKendaraan', 'totalArea', 'userTerbaru'));
    }
}
