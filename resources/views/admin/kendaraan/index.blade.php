@extends('layouts.app')
@section('title','Kelola Kendaraan')
@section('breadcrumb') Kelola / <span>Kendaraan</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link"><i class="bi bi-tag"></i> Kelola Tarif Parkir</a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link"><i class="bi bi-map"></i> Kelola Area Parkir</a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link active"><i class="bi bi-car-front"></i> Kelola Kendaraan</a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Kendaraan</h5>
    <a href="{{ route('admin.kendaraan.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kendaraan
    </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.kendaraan.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:200px;">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Plat nomor atau pemilik..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Jenis</label>
                <select name="jenis" class="form-select" style="min-width:130px;">
                    <option value="">Semua Jenis</option>
                    <option value="motor"   {{ request('jenis')=='motor'   ? 'selected':'' }}>Motor</option>
                    <option value="mobil"   {{ request('jenis')=='mobil'   ? 'selected':'' }}>Mobil</option>
                    <option value="lainnya" {{ request('jenis')=='lainnya' ? 'selected':'' }}>Lainnya</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->hasAny(['search','jenis']))
            <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
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
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Warna</th>
                    <th>Pemilik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kendaraan as $i => $k)
                <tr>
                    <td class="ps-4">{{ $kendaraan->firstItem() + $i }}</td>
                    <td class="fw-bold" style="letter-spacing:.5px;">{{ $k->plat_nomor }}</td>
                    <td class="text-capitalize">{{ $k->jenis_kendaraan }}</td>
                    <td>{{ $k->warna }}</td>
                    <td>{{ $k->pemilik }}</td>
                    <td>
                        <a href="{{ route('admin.kendaraan.edit', $k) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.kendaraan.destroy', $k) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus kendaraan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada kendaraan yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kendaraan->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <span style="font-size:.8rem;color:#6b7280;">Menampilkan {{ $kendaraan->firstItem() }}–{{ $kendaraan->lastItem() }} dari {{ $kendaraan->total() }} kendaraan</span>
        {{ $kendaraan->links('vendor.pagination.simple-custom') }}
    </div>
    @endif
</div>
@endsection
