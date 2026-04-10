<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TbTransaksi;
use App\Models\TbKendaraan;
use App\Models\TbTarif;
use App\Models\TbAreaParkir;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\QrHelper;
use App\Helpers\BiayaHelper;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = TbTransaksi::with(['kendaraan', 'area'])->where('status', 'masuk');

        if ($request->filled('search')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('plat_nomor', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('id_area')) {
            $query->where('id_area', $request->id_area);
        }

        $transaksiMasuk = $query->latest('waktu_masuk')->paginate(10)->withQueryString();
        $areas = TbAreaParkir::all();
        $tarif = TbTarif::all();

        return view('petugas.transaksi.index', compact('transaksiMasuk', 'areas', 'tarif'));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'id_area'         => 'required|exists:tb_area_parkir,id',
        ]);

        // Cari atau buat kendaraan
        $kendaraan = TbKendaraan::firstOrCreate(
            ['plat_nomor' => strtoupper($request->plat_nomor)],
            [
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'warna'           => '-',
                'pemilik'         => 'Tidak Diketahui',
            ]
        );

        // Cek apakah kendaraan sudah masuk
        $sudahMasuk = TbTransaksi::where('id_kendaraan', $kendaraan->id)
            ->where('status', 'masuk')->exists();

        if ($sudahMasuk) {
            return back()->with('error', 'Kendaraan ' . $kendaraan->plat_nomor . ' sudah tercatat masuk.');
        }

        // Ambil tarif sesuai jenis
        $tarif = TbTarif::where('jenis_kendaraan', $request->jenis_kendaraan)->first();
        if (!$tarif) {
            return back()->with('error', 'Tarif untuk jenis kendaraan ini belum diatur.');
        }

        // Cek kapasitas area
        $area = TbAreaParkir::findOrFail($request->id_area);
        if ($area->terisi >= $area->kapasitas) {
            return back()->with('error', 'Area parkir sudah penuh.');
        }

        $transaksi = TbTransaksi::create([
            'id_kendaraan' => $kendaraan->id,
            'waktu_masuk'  => now(),
            'id_tarif'     => $tarif->id,
            'status'       => 'masuk',
            'id_user'      => auth()->id(),
            'id_area'      => $area->id,
        ]);

        $area->increment('terisi');

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Kendaraan masuk: ' . $kendaraan->plat_nomor,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('petugas.transaksi.struk', $transaksi->id)
            ->with('success', 'Kendaraan berhasil dicatat masuk.');
    }

    public function formKeluar()
    {
        return view('petugas.transaksi.keluar');
    }

    public function keluar(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string',
        ]);

        $kendaraan = TbKendaraan::where('plat_nomor', strtoupper($request->plat_nomor))->first();

        if (!$kendaraan) {
            return back()->with('error', 'Kendaraan tidak ditemukan.')->withInput();
        }

        $transaksi = TbTransaksi::with(['tarif', 'area'])
            ->where('id_kendaraan', $kendaraan->id)
            ->where('status', 'masuk')
            ->latest('waktu_masuk')
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Tidak ada transaksi masuk untuk kendaraan ini.')->withInput();
        }

        $waktuKeluar = now();
        $durasi      = BiayaHelper::hitungDurasi($transaksi->waktu_masuk, $waktuKeluar);
        $biaya       = BiayaHelper::hitungBiaya($durasi, $transaksi->tarif->tarif_per_jam);

        // Simpan info ke session untuk ditampilkan di form bayar
        session([
            'bayar_transaksi_id' => $transaksi->id,
            'bayar_durasi'       => $durasi,
            'bayar_biaya'        => $biaya,
            'bayar_waktu_keluar' => $waktuKeluar->toDateTimeString(),
        ]);

        return redirect()->route('petugas.transaksi.bayar.form', $transaksi->id);
    }

    public function formBayar($id)
    {
        $transaksi = TbTransaksi::with(['kendaraan', 'tarif', 'area'])->findOrFail($id);

        // Hitung ulang biaya saat ini
        $durasi = session('bayar_durasi') ?? BiayaHelper::hitungDurasi($transaksi->waktu_masuk, now());
        $biaya  = session('bayar_biaya')  ?? BiayaHelper::hitungBiaya($durasi, $transaksi->tarif->tarif_per_jam);

        $capHarian = BiayaHelper::capHarian($transaksi->tarif->tarif_per_jam);

        return view('petugas.transaksi.bayar', compact('transaksi', 'durasi', 'biaya', 'capHarian'));
    }

    public function prosesBayar(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        $transaksi   = TbTransaksi::with(['kendaraan', 'tarif', 'area'])->findOrFail($id);
        $durasi      = session('bayar_durasi', BiayaHelper::hitungDurasi($transaksi->waktu_masuk, now()));
        $biaya       = session('bayar_biaya',  BiayaHelper::hitungBiaya($durasi, $transaksi->tarif->tarif_per_jam));
        $jumlahBayar = (float) $request->jumlah_bayar;

        if ($jumlahBayar < $biaya) {
            return back()->with('error', 'Jumlah bayar kurang dari total biaya.')->withInput();
        }

        $kembalian   = $jumlahBayar - $biaya;
        $waktuKeluar = session('bayar_waktu_keluar') ? \Carbon\Carbon::parse(session('bayar_waktu_keluar')) : now();

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam'   => $durasi,
            'biaya_total'  => $biaya,
            'status'       => 'keluar',
        ]);

        $transaksi->area->decrement('terisi');

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Kendaraan keluar: ' . $transaksi->kendaraan->plat_nomor . ' | Biaya: Rp ' . number_format($biaya, 0, ',', '.') . ' | Bayar: Rp ' . number_format($jumlahBayar, 0, ',', '.') . ' | Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'),
            'waktu_aktivitas'=> now(),
        ]);

        session()->forget(['bayar_transaksi_id', 'bayar_durasi', 'bayar_biaya', 'bayar_waktu_keluar']);

        return redirect()->route('petugas.transaksi.struk', $transaksi->id)
            ->with('success', 'Pembayaran berhasil.')
            ->with('kembalian', $kembalian)
            ->with('jumlah_bayar', $jumlahBayar);
    }

    public function struk($id)
    {
        $transaksi   = TbTransaksi::with(['kendaraan', 'tarif', 'area', 'user'])->findOrFail($id);
        $kembalian   = session('kembalian');
        $jumlahBayar = session('jumlah_bayar');
        $qrCode      = QrHelper::toSvg($transaksi->kendaraan->plat_nomor);
        return view('petugas.transaksi.struk', compact('transaksi', 'kembalian', 'jumlahBayar', 'qrCode'));
    }

    public function cetakPdf($id)
    {
        $transaksi   = TbTransaksi::with(['kendaraan', 'tarif', 'area', 'user'])->findOrFail($id);
        $kembalian   = session('kembalian');
        $jumlahBayar = session('jumlah_bayar');
        $qrDataUri   = QrHelper::toDataUri($transaksi->kendaraan->plat_nomor);
        $pdf = Pdf::loadView('petugas.transaksi.struk-pdf', compact('transaksi', 'kembalian', 'jumlahBayar', 'qrDataUri'))
            ->setPaper([0, 0, 226.77, 500], 'portrait');

        return $pdf->download('struk-parkir-' . $transaksi->kendaraan->plat_nomor . '.pdf');
    }
}
