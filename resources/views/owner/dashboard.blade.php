@extends('layouts.app')
@section('title','Dashboard Owner')
@section('breadcrumb') <span>Dashboard Owner</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('owner.dashboard') }}" class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('owner.rekap') }}" class="sidebar-link {{ request()->routeIs('owner.rekap') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i> Rekap Transaksi
    </a>
@endsection

@section('content')
<h5 class="fw-bold mb-4">Ringkasan Pendapatan</h5>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff;color:#1e3a8a;"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value" style="font-size:1.3rem;">Rp {{ number_format($pendapatanHari, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-car-front-fill"></i></div>
            <div>
                <div class="stat-label">Kendaraan Hari Ini</div>
                <div class="stat-value">{{ $kendaraanHari }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fefce8;color:#ca8a04;"><i class="bi bi-calendar-month"></i></div>
            <div>
                <div class="stat-label">Pendapatan Bulan Ini</div>
                <div class="stat-value" style="font-size:1.3rem;">Rp {{ number_format($pendapatanBulan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Rekap 7 Hari Terakhir</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Jumlah Kendaraan</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap7Hari as $r)
                <tr>
                    <td class="ps-4">{{ $r['tanggal'] }}</td>
                    <td>{{ $r['kendaraan'] }}</td>
                    <td>Rp {{ number_format($r['pendapatan'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top text-end">
        <a href="{{ route('owner.rekap') }}" class="btn btn-primary btn-sm px-3">
            Lihat Rekap Lengkap <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>
@endsection
