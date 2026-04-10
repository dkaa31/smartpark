@extends('layouts.app')
@section('title','Kelola Area Parkir')
@section('breadcrumb') Kelola / <span>Area Parkir</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link"><i class="bi bi-tag"></i> Kelola Tarif Parkir</a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link active"><i class="bi bi-map"></i> Kelola Area Parkir</a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link"><i class="bi bi-car-front"></i> Kelola Kendaraan</a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Area Parkir</h5>
    <a href="{{ route('admin.area.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Tambah Area
    </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.area.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:200px;">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama area..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Status</label>
                <select name="status" class="form-select" style="min-width:140px;">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status')=='tersedia' ? 'selected':'' }}>Tersedia</option>
                    <option value="penuh"    {{ request('status')=='penuh'    ? 'selected':'' }}>Penuh</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.area.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
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
                    <th>Nama Area</th>
                    <th>Kapasitas</th>
                    <th>Terisi</th>
                    <th>Sisa</th>
                    <th>Penggunaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $i => $area)
                @php $persen = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0; @endphp
                <tr>
                    <td class="ps-4">{{ $areas->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $area->nama_area }}</td>
                    <td>{{ $area->kapasitas }}</td>
                    <td>
                        <span class="{{ $area->terisi >= $area->kapasitas ? 'text-danger fw-bold' : '' }}">
                            {{ $area->terisi }}
                        </span>
                    </td>
                    <td>
                        @if($area->kapasitas - $area->terisi === 0)
                            <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:.75rem;">Penuh</span>
                        @else
                            <span style="color:#166534;">{{ $area->kapasitas - $area->terisi }}</span>
                        @endif
                    </td>
                    <td style="min-width:120px;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;border-radius:3px;">
                                <div class="progress-bar {{ $persen >= 90 ? 'bg-danger' : ($persen >= 70 ? 'bg-warning' : 'bg-success') }}"
                                    style="width:{{ $persen }}%;border-radius:3px;"></div>
                            </div>
                            <span style="font-size:.75rem;color:#6b7280;white-space:nowrap;">{{ $persen }}%</span>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.area.edit', $area) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.area.destroy', $area) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus area ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada area yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($areas->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <span style="font-size:.8rem;color:#6b7280;">Menampilkan {{ $areas->firstItem() }}–{{ $areas->lastItem() }} dari {{ $areas->total() }} area</span>
        {{ $areas->links() }}
    </div>
    @endif
</div>
@endsection
