@extends('layouts.app')
@section('title','Rekap Transaksi')
@section('breadcrumb') <span>Rekap Transaksi</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('owner.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="{{ route('owner.rekap') }}" class="sidebar-link active"><i class="bi bi-bar-chart-line"></i> Rekap Transaksi</a>
@endsection

@section('content')
@php
    $namaBulan = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
        5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
        9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
    ];
    $judulPeriode = $mode === 'harian'
        ? \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')
        : ($namaBulan[$bulan] . ' ' . $tahun);
@endphp

<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1">Laporan Transaksi</h5>
        <p class="text-muted mb-0" style="font-size:.875rem;">
            {{ $mode === 'harian' ? 'Rekap per jam —' : 'Rekap harian —' }} {{ $judulPeriode }}
        </p>
    </div>
    @if($rekap->count())
    <a href="{{ route('owner.rekap.pdf', array_merge(request()->query(), ['mode' => $mode])) }}"
        class="btn btn-primary px-4" target="_blank">
        <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
    </a>
    @endif
</div>

{{-- Tab Mode --}}
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('owner.rekap', ['mode' => 'bulanan', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
        class="btn btn-sm {{ $mode === 'bulanan' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-calendar-month me-1"></i>Per Bulan
    </a>
    <a href="{{ route('owner.rekap', ['mode' => 'harian', 'tanggal' => $tanggal ?? now()->format('Y-m-d')]) }}"
        class="btn btn-sm {{ $mode === 'harian' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-calendar-day me-1"></i>Per Hari
    </a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body p-3">
        @if($mode === 'bulanan')
        <form method="GET" action="{{ route('owner.rekap') }}" class="d-flex align-items-end gap-3 flex-wrap">
            <input type="hidden" name="mode" value="bulanan">
            <div>
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Bulan</label>
                <select name="bulan" class="form-select" style="min-width:150px;">
                    @foreach($namaBulan as $num => $nama)
                    <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Tahun</label>
                <select name="tahun" class="form-select" style="min-width:110px;">
                    @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-search me-1"></i>Tampilkan
            </button>
        </form>
        @else
        <form method="GET" action="{{ route('owner.rekap') }}" class="d-flex align-items-end gap-3 flex-wrap">
            <input type="hidden" name="mode" value="harian">
            <div>
                <label class="form-label fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? now()->format('Y-m-d') }}" style="min-width:180px;">
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-search me-1"></i>Tampilkan
            </button>
            {{-- Navigasi hari --}}
            @if($tanggal)
            <a href="{{ route('owner.rekap', ['mode' => 'harian', 'tanggal' => \Carbon\Carbon::parse($tanggal)->subDay()->format('Y-m-d')]) }}"
                class="btn btn-outline-secondary px-3" title="Hari sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
            <a href="{{ route('owner.rekap', ['mode' => 'harian', 'tanggal' => \Carbon\Carbon::parse($tanggal)->addDay()->format('Y-m-d')]) }}"
                class="btn btn-outline-secondary px-3" title="Hari berikutnya"
                {{ \Carbon\Carbon::parse($tanggal)->isToday() ? 'style=opacity:.4;pointer-events:none' : '' }}>
                <i class="bi bi-chevron-right"></i>
            </a>
            @endif
        </form>
        @endif
    </div>
</div>

{{-- Summary Cards --}}
@if($rekap->count())
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff;color:#1e3a8a;">
                <i class="bi bi-{{ $mode === 'harian' ? 'clock' : 'calendar-check' }}"></i>
            </div>
            <div>
                <div class="stat-label">{{ $mode === 'harian' ? 'Jam Aktif' : 'Total Hari' }}</div>
                <div class="stat-value">{{ $rekap->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-car-front-fill"></i></div>
            <div>
                <div class="stat-label">Total Kendaraan</div>
                <div class="stat-value">{{ $rekap->sum('jumlah_kendaraan') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fefce8;color:#ca8a04;"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size:1.2rem;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ $mode === 'harian' ? 'Rekap Per Jam' : 'Rekap Harian' }} — {{ $judulPeriode }}</span>
        @if($rekap->count())
        <span style="font-size:.8rem;color:#6b7280;font-weight:400;">{{ $rekap->sum('jumlah_kendaraan') }} kendaraan</span>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">#</th>
                    <th>{{ $mode === 'harian' ? 'Jam' : 'Tanggal' }}</th>
                    <th>Jumlah Kendaraan</th>
                    <th class="text-end pe-4">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $i => $r)
                <tr>
                    <td class="ps-4">{{ $i + 1 }}</td>
                    <td>
                        @if($mode === 'harian')
                            {{ str_pad($r->jam, 2, '0', STR_PAD_LEFT) }}:00 – {{ str_pad($r->jam, 2, '0', STR_PAD_LEFT) }}:59
                        @else
                            {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}
                        @endif
                    </td>
                    <td>{{ $r->jumlah_kendaraan }}</td>
                    <td class="text-end pe-4">Rp {{ number_format($r->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                        Tidak ada data untuk {{ $judulPeriode }}
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($rekap->count())
            <tfoot>
                <tr style="background:#f9fafb;">
                    <td class="ps-4 fw-bold" colspan="3">Total {{ $judulPeriode }}</td>
                    <td class="text-end pe-4 fw-bold" style="color:#1e3a8a;font-size:1rem;">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
