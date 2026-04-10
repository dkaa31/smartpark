@extends('layouts.app')
@section('title','Edit Tarif')
@section('breadcrumb') Kelola / <a href="{{ route('admin.tarif.index') }}" style="color:inherit;">Tarif</a> / <span>Edit</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link active"><i class="bi bi-tag"></i> Kelola Tarif Parkir</a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link"><i class="bi bi-map"></i> Kelola Area Parkir</a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link"><i class="bi bi-car-front"></i> Kelola Kendaraan</a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tarif.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Edit Tarif Parkir</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2" style="color:#1e3a8a;"></i>Ubah Data Tarif</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.tarif.update', $tarif) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select" required>
                            <option value="motor"   {{ old('jenis_kendaraan',$tarif->jenis_kendaraan)=='motor'   ? 'selected':'' }}>Motor</option>
                            <option value="mobil"   {{ old('jenis_kendaraan',$tarif->jenis_kendaraan)=='mobil'   ? 'selected':'' }}>Mobil</option>
                            <option value="lainnya" {{ old('jenis_kendaraan',$tarif->jenis_kendaraan)=='lainnya' ? 'selected':'' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tarif per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" class="form-control"
                            value="{{ old('tarif_per_jam', $tarif->tarif_per_jam) }}" min="0" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                        <a href="{{ route('admin.tarif.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#f8fafc;border-color:#e5e7eb;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#374151;"><i class="bi bi-receipt me-2"></i>Tarif Saat Ini</div>
                <dl class="row mb-0" style="font-size:.875rem;">
                    <dt class="col-6 text-muted">Jenis Kendaraan</dt>
                    <dd class="col-6 text-capitalize fw-semibold">{{ $tarif->jenis_kendaraan }}</dd>
                    <dt class="col-6 text-muted">Tarif per Jam</dt>
                    <dd class="col-6 fw-bold" style="color:#1e3a8a;">Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</dd>
                    <dt class="col-6 text-muted">Contoh 2 jam</dt>
                    <dd class="col-6">Rp {{ number_format($tarif->tarif_per_jam * 2, 0, ',', '.') }}</dd>
                    <dt class="col-6 text-muted">Contoh 5 jam</dt>
                    <dd class="col-6">Rp {{ number_format($tarif->tarif_per_jam * 5, 0, ',', '.') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
