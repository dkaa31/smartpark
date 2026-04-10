@extends('layouts.app')
@section('title','Log Aktivitas')
@section('breadcrumb') Laporan / <span>Log Aktivitas</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link"><i class="bi bi-tag"></i> Kelola Tarif Parkir</a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link"><i class="bi bi-map"></i> Kelola Area Parkir</a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link"><i class="bi bi-car-front"></i> Kelola Kendaraan</a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link active"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
@endsection

@section('content')
<h5 class="fw-bold mb-3">Log Aktivitas</h5>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.log') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:200px;">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Cari Aktivitas</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Kata kunci aktivitas..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Pengguna</label>
                <select name="id_user" class="form-select" style="min-width:160px;">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('id_user') == $u->id ? 'selected' : '' }}>
                        {{ $u->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="min-width:160px;">
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->hasAny(['search','id_user','tanggal']))
            <a href="{{ route('admin.log') }}" class="btn btn-outline-secondary px-3">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">#</th>
                    <th>Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                <tr>
                    <td class="ps-4">{{ $logs->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-semibold">{{ $log->user->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:.75rem;color:#6b7280;text-transform:capitalize;">{{ $log->user->role ?? '' }}</div>
                    </td>
                    <td>{{ $log->aktivitas }}</td>
                    <td style="white-space:nowrap;font-size:.8rem;color:#6b7280;">
                        {{ $log->waktu_aktivitas->format('d M Y, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada log yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <span style="font-size:.8rem;color:#6b7280;">Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log</span>
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
