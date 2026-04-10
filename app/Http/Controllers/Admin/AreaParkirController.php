<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbAreaParkir;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;

class AreaParkirController extends Controller
{
    public function index(Request $request)
    {
        $query = TbAreaParkir::query();

        if ($request->filled('search')) {
            $query->where('nama_area', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            if ($request->status === 'penuh') {
                $query->whereColumn('terisi', '>=', 'kapasitas');
            } elseif ($request->status === 'tersedia') {
                $query->whereColumn('terisi', '<', 'kapasitas');
            }
        }

        $areas = $query->latest()->paginate(10)->withQueryString();
        return view('admin.area.index', compact('areas'));
    }

    public function show(TbAreaParkir $area)
    {
        return redirect()->route('admin.area.edit', $area);
    }

    public function create()
    {
        return view('admin.area.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area'  => 'required|string|max:100',
            'kapasitas'  => 'required|integer|min:1',
        ]);

        TbAreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi'    => 0,
        ]);

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menambah area parkir: ' . $request->nama_area,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function edit(TbAreaParkir $area)
    {
        return view('admin.area.edit', compact('area'));
    }

    public function update(Request $request, TbAreaParkir $area)
    {
        $request->validate([
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'terisi'    => 'required|integer|min:0',
        ]);

        $area->update($request->only('nama_area', 'kapasitas', 'terisi'));

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Mengubah area parkir: ' . $area->nama_area,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy(TbAreaParkir $area)
    {
        $nama = $area->nama_area;
        $area->delete();

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menghapus area parkir: ' . $nama,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil dihapus.');
    }
}
