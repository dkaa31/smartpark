<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbLogAktivitas;
use App\Models\TbUser;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = TbLogAktivitas::with('user');

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_aktivitas', $request->tanggal);
        }

        $logs  = $query->latest('waktu_aktivitas')->paginate(20)->withQueryString();
        $users = TbUser::orderBy('nama_lengkap')->get();

        return view('admin.log.index', compact('logs', 'users'));
    }
}
