@extends('layouts.app')
@section('title','Tambah Area')
@section('breadcrumb') Kelola / <a href="{{ route('admin.area.index') }}" style="color:inherit;">Area Parkir</a> / <span>Tambah</span> @endsection

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
    <h5 class="fw-bold mb-0">Tambah Area Parkir</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-map me-2" style="color:#1e3a8a;"></i>Data Area Baru</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.area.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Area</label>
                        <input type="text" name="nama_area" class="form-control @error('nama_area') is-invalid @enderror"
                            value="{{ old('nama_area') }}" placeholder="Contoh: Area A - Lantai 1" required>
                        @error('nama_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kapasitas (slot)</label>
                        <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror"
                            value="{{ old('kapasitas') }}" min="1" placeholder="Jumlah slot parkir" required>
                        @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Jumlah total slot parkir yang tersedia di area ini.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('admin.area.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#1e3a8a;"><i class="bi bi-info-circle me-2"></i>Informasi</div>
                <p class="text-muted mb-3" style="font-size:.875rem;">Area parkir baru akan dimulai dengan kapasitas terisi = 0.</p>
                <div class="p-3 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                    <div class="fw-semibold mb-2" style="font-size:.875rem;color:#1e3a8a;">Contoh Penamaan Area:</div>
                    <ul class="mb-0 ps-3" style="font-size:.8rem;color:#374151;">
                        <li>Area A - Lantai 1</li>
                        <li>Area B - Basement</li>
                        <li>Zona Motor - Depan</li>
                        <li>Parkir VIP</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
