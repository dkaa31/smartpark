<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TbTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    // mode: 'bulanan' atau 'harian'
    private function parsePeriode(Request $request): array
    {
        $mode = $request->input('mode', 'bulanan');

        if ($mode === 'harian') {
            $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
            $dari    = $tanggal;
            $sampai  = $tanggal;
            $bulan   = (int) Carbon::parse($tanggal)->month;
            $tahun   = (int) Carbon::parse($tanggal)->year;
        } else {
            $bulan  = (int) $request->input('bulan', now()->month);
            $tahun  = (int) $request->input('tahun', now()->year);
            $dari   = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
            $sampai = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');
            $tanggal = null;
        }

        return [$mode, $bulan, $tahun, $dari, $sampai, $tanggal];
    }

    private function getRekapData(string $dari, string $sampai, string $mode)
    {
        $query = TbTransaksi::where('status', 'keluar')
            ->whereDate('waktu_keluar', '>=', $dari)
            ->whereDate('waktu_keluar', '<=', $sampai);

        if ($mode === 'harian') {
            // Per jam untuk mode harian
            return $query
                ->selectRaw('HOUR(waktu_keluar) as jam, COUNT(*) as jumlah_kendaraan, SUM(biaya_total) as total_pendapatan')
                ->groupBy('jam')
                ->orderBy('jam')
                ->get();
        }

        // Per hari untuk mode bulanan
        return $query
            ->selectRaw('DATE(waktu_keluar) as tanggal, COUNT(*) as jumlah_kendaraan, SUM(biaya_total) as total_pendapatan')
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->get();
    }

    public function index(Request $request)
    {
        [$mode, $bulan, $tahun, $dari, $sampai, $tanggal] = $this->parsePeriode($request);

        $rekap      = $this->getRekapData($dari, $sampai, $mode);
        $grandTotal = $rekap->sum('total_pendapatan');

        $tahunPertama = TbTransaksi::selectRaw('MIN(YEAR(waktu_masuk)) as thn')->value('thn') ?? now()->year;
        $tahunList    = range(now()->year, $tahunPertama);

        return view('owner.rekap', compact(
            'rekap', 'dari', 'sampai', 'grandTotal',
            'bulan', 'tahun', 'tahunList', 'mode', 'tanggal'
        ));
    }

    public function downloadPdf(Request $request)
    {
        [$mode, $bulan, $tahun, $dari, $sampai, $tanggal] = $this->parsePeriode($request);

        $rekap      = $this->getRekapData($dari, $sampai, $mode);
        $grandTotal = $rekap->sum('total_pendapatan');

        $pdf = Pdf::loadView('owner.rekap-pdf', compact('rekap', 'dari', 'sampai', 'grandTotal', 'mode'))
            ->setPaper('a4', 'portrait');

        $filename = $mode === 'harian'
            ? 'rekap-harian-' . $dari . '.pdf'
            : 'rekap-bulanan-' . Carbon::createFromDate($tahun, $bulan, 1)->format('F-Y') . '.pdf';

        return $pdf->download($filename);
    }
}
