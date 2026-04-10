<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; }

        .header { text-align: center; padding: 20px 0 16px; border-bottom: 2px solid #1e3a8a; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; color: #1e3a8a; margin-bottom: 2px; }
        .header p  { font-size: 11px; color: #6b7280; }

        .meta-table { width: 100%; margin-bottom: 16px; }
        .meta-table td { padding: 2px 0; font-size: 11px; }
        .meta-table td:first-child { color: #6b7280; width: 140px; }
        .meta-table td:last-child { font-weight: 600; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table td { padding: 10px 14px; font-size: 11px; border: 1px solid #e5e7eb; background: #f9fafb; }
        .summary-table .label { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
        .summary-table .value { font-size: 14px; font-weight: bold; color: #1e3a8a; margin-top: 2px; }
        .summary-table .highlight { background: #eff6ff; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data thead tr { background: #1e3a8a; color: #fff; }
        table.data thead th { padding: 9px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
        table.data tbody tr:nth-child(even) { background: #f9fafb; }
        table.data tbody td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        table.data tfoot tr { background: #eff6ff; }
        table.data tfoot td { padding: 10px 12px; font-weight: bold; font-size: 12px; border-top: 2px solid #1e3a8a; color: #1e3a8a; }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .footer { margin-top: 32px; border-top: 1px solid #e5e7eb; padding-top: 12px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>SmartPark</h1>
        <p>Laporan Rekap Transaksi Parkir</p>
    </div>

    {{-- Meta --}}
    <table class="meta-table">
        <tr>
            <td>Periode</td>
            <td>: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }}
                @if($dari !== $sampai) — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }} @endif
            </td>
        </tr>
        <tr>
            <td>Mode</td>
            <td>: {{ $mode === 'harian' ? 'Rekap Per Jam' : 'Rekap Harian' }}</td>
        </tr>
        <tr>
            <td>Dicetak pada</td>
            <td>: {{ now()->format('d M Y, H:i') }}</td>
        </tr>
    </table>

    {{-- Summary — pakai table bukan flexbox agar DomPDF support --}}
    <table class="summary-table">
        <tr>
            <td style="width:32%;">
                <div class="label">{{ $mode === 'harian' ? 'Jam Aktif' : 'Total Hari' }}</div>
                <div class="value">{{ $rekap->count() }}</div>
            </td>
            <td style="width:4%;border:none;background:transparent;"></td>
            <td style="width:32%;">
                <div class="label">Total Kendaraan</div>
                <div class="value">{{ $rekap->sum('jumlah_kendaraan') }}</div>
            </td>
            <td style="width:4%;border:none;background:transparent;"></td>
            <td style="width:32%;" class="highlight">
                <div class="label">Total Pendapatan</div>
                <div class="value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Data --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:8%;">#</th>
                <th>{{ $mode === 'harian' ? 'Jam' : 'Tanggal' }}</th>
                <th class="text-center" style="width:22%;">Jumlah Kendaraan</th>
                <th class="text-right" style="width:28%;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    @if($mode === 'harian')
                        {{ str_pad($r->jam, 2, '0', STR_PAD_LEFT) }}:00 &ndash; {{ str_pad($r->jam, 2, '0', STR_PAD_LEFT) }}:59
                    @else
                        {{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-center">{{ $r->jumlah_kendaraan }}</td>
                <td class="text-right">Rp {{ number_format($r->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding:20px;color:#6b7280;">
                    Tidak ada data untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($rekap->count())
        <tfoot>
            <tr>
                <td colspan="2">Total Periode Ini</td>
                <td class="text-center">{{ $rekap->sum('jumlah_kendaraan') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem SmartPark &bull; {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
