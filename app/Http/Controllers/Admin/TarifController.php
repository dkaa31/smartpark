<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbTarif;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index(Request $request)
    {
        $query = TbTarif::query();

        if ($request->filled('jenis')) {
            $query->where('jenis_kendaraan', $request->jenis);
        }

        $tarif = $query->get();
        return view('admin.tarif.index', compact('tarif'));
    }

    public function show(TbTarif $tarif)
    {
        return redirect()->route('admin.tarif.edit', $tarif);
    }

    public function create()
    {
        return view('admin.tarif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        TbTarif::create($request->only('jenis_kendaraan', 'tarif_per_jam'));

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menambah tarif: ' . $request->jenis_kendaraan,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit(TbTarif $tarif)
    {
        return view('admin.tarif.edit', compact('tarif'));
    }

    public function update(Request $request, TbTarif $tarif)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        $tarif->update($request->only('jenis_kendaraan', 'tarif_per_jam'));

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Mengubah tarif: ' . $tarif->jenis_kendaraan,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(TbTarif $tarif)
    {
        $jenis = $tarif->jenis_kendaraan;
        $tarif->delete();

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menghapus tarif: ' . $jenis,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
