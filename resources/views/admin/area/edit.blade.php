@extends('layouts.app')
@section('title','Edit Area')
@section('breadcrumb') Kelola / <a href="{{ route('admin.area.index') }}" style="color:inherit;">Area Parkir</a> / <span>Edit</span> @endsection

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
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.area.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Edit Area Parkir</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2" style="color:#1e3a8a;"></i>Ubah Data Area</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.area.update', $area) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Area</label>
                        <input type="text" name="nama_area" class="form-control"
                            value="{{ old('nama_area', $area->nama_area) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control"
                            value="{{ old('kapasitas', $area->kapasitas) }}" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Terisi Saat Ini</label>
                        <input type="number" name="terisi" class="form-control"
                            value="{{ old('terisi', $area->terisi) }}" min="0" required>
                        <div class="form-text">Sesuaikan jika ada perbedaan data fisik.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                        <a href="{{ route('admin.area.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#f8fafc;border-color:#e5e7eb;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#374151;"><i class="bi bi-map me-2"></i>Status Area Saat Ini</div>
                <dl class="row mb-3" style="font-size:.875rem;">
                    <dt class="col-6 text-muted">Nama Area</dt>
                    <dd class="col-6 fw-semibold">{{ $area->nama_area }}</dd>
                    <dt class="col-6 text-muted">Kapasitas</dt>
                    <dd class="col-6">{{ $area->kapasitas }} slot</dd>
                    <dt class="col-6 text-muted">Terisi</dt>
                    <dd class="col-6">{{ $area->terisi }} slot</dd>
                    <dt class="col-6 text-muted">Tersedia</dt>
                    <dd class="col-6 fw-bold" style="color:#166534;">{{ $area->kapasitas - $area->terisi }} slot</dd>
                </dl>
                {{-- Progress bar --}}
                @php $persen = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0; @endphp
                <div style="font-size:.75rem;color:#6b7280;margin-bottom:4px;">Kapasitas terisi: {{ $persen }}%</div>
                <div class="progress" style="height:8px;border-radius:4px;">
                    <div class="progress-bar {{ $persen >= 90 ? 'bg-danger' : ($persen >= 70 ? 'bg-warning' : 'bg-success') }}"
                        style="width:{{ $persen }}%;border-radius:4px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
