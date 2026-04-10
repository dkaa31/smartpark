@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    Ikhtisar / <span>Dashboard Admin</span>
@endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Kelola User
    </a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link {{ request()->routeIs('admin.tarif.*') ? 'active' : '' }}">
        <i class="bi bi-tag"></i> Kelola Tarif Parkir
    </a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link {{ request()->routeIs('admin.area.*') ? 'active' : '' }}">
        <i class="bi bi-map"></i> Kelola Area Parkir
    </a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link {{ request()->routeIs('admin.kendaraan.*') ? 'active' : '' }}">
        <i class="bi bi-car-front"></i> Kelola Kendaraan
    </a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link {{ request()->routeIs('admin.log') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Log Aktivitas
    </a>
@endsection

@section('content')
<h5 class="fw-bold mb-4" style="color:#111827;">Statistik Sistem</h5>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#eff6ff;color:#1e3a8a;"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value">{{ number_format($totalUser) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-car-front-fill"></i></div>
            <div>
                <div class="stat-label">Total Kendaraan</div>
                <div class="stat-value">{{ number_format($totalKendaraan) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fefce8;color:#ca8a04;"><i class="bi bi-map-fill"></i></div>
            <div>
                <div class="stat-label">Total Area</div>
                <div class="stat-value">{{ $totalArea }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Pengguna Terbaru</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Nama</th>
                    <th>Peran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userTerbaru as $user)
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold" style="color:#111827;">{{ $user->nama_lengkap }}</div>
                        <div style="font-size:.75rem;color:#6b7280;">{{ $user->username }}</div>
                    </td>
                    <td><span style="text-transform:capitalize;">{{ $user->role }}</span></td>
                    <td>
                        @if($user->status_aktif === 'aktif')
                            <span class="badge badge-aktif px-2 py-1 rounded-pill" style="font-size:.75rem;">AKTIF</span>
                        @else
                            <span class="badge badge-nonaktif px-2 py-1 rounded-pill" style="font-size:.75rem;">NONAKTIF</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($userTerbaru->count())
    <div class="card-footer bg-white" style="font-size:.8rem;color:#6b7280;border-top:1px solid #e5e7eb;">
        Menampilkan {{ $userTerbaru->count() }} entri terbaru
    </div>
    @endif
</div>
@endsection
