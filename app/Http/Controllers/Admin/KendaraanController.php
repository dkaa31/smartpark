<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbKendaraan;
use App\Models\TbUser;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = TbKendaraan::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('plat_nomor', 'like', '%' . $request->search . '%')
                  ->orWhere('pemilik', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('jenis')) {
            $query->where('jenis_kendaraan', $request->jenis);
        }

        $kendaraan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kendaraan.index', compact('kendaraan'));
    }

    public function show(TbKendaraan $kendaraan)
    {
        return redirect()->route('admin.kendaraan.edit', $kendaraan);
    }

    public function create()
    {
        $users = TbUser::where('status_aktif', 'aktif')->get();
        return view('admin.kendaraan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|unique:tb_kendaraan,plat_nomor|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'warna'           => 'required|string|max:50',
            'pemilik'         => 'required|string|max:100',
            'id_user'         => 'nullable|exists:tb_user,id',
        ]);

        TbKendaraan::create($request->only('plat_nomor', 'jenis_kendaraan', 'warna', 'pemilik', 'id_user'));

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menambah kendaraan: ' . $request->plat_nomor,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(TbKendaraan $kendaraan)
    {
        $users = TbUser::where('status_aktif', 'aktif')->get();
        return view('admin.kendaraan.edit', compact('kendaraan', 'users'));
    }

    public function update(Request $request, TbKendaraan $kendaraan)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|unique:tb_kendaraan,plat_nomor,' . $kendaraan->id . '|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'warna'           => 'required|string|max:50',
            'pemilik'         => 'required|string|max:100',
            'id_user'         => 'nullable|exists:tb_user,id',
        ]);

        $kendaraan->update($request->only('plat_nomor', 'jenis_kendaraan', 'warna', 'pemilik', 'id_user'));

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Mengubah kendaraan: ' . $kendaraan->plat_nomor,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(TbKendaraan $kendaraan)
    {
        $plat = $kendaraan->plat_nomor;
        $kendaraan->delete();

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menghapus kendaraan: ' . $plat,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
