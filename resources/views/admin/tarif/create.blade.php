@extends('layouts.app')
@section('title','Tambah Tarif')
@section('breadcrumb') Kelola / <a href="{{ route('admin.tarif.index') }}" style="color:inherit;">Tarif</a> / <span>Tambah</span> @endsection

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
    <h5 class="fw-bold mb-0">Tambah Tarif Parkir</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-tag me-2" style="color:#1e3a8a;"></i>Data Tarif Baru</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.tarif.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select @error('jenis_kendaraan') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="motor"   {{ old('jenis_kendaraan')=='motor'   ? 'selected':'' }}>Motor</option>
                            <option value="mobil"   {{ old('jenis_kendaraan')=='mobil'   ? 'selected':'' }}>Mobil</option>
                            <option value="lainnya" {{ old('jenis_kendaraan')=='lainnya' ? 'selected':'' }}>Lainnya</option>
                        </select>
                        @error('jenis_kendaraan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tarif per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" class="form-control @error('tarif_per_jam') is-invalid @enderror"
                            value="{{ old('tarif_per_jam') }}" min="0" placeholder="Contoh: 3000" required>
                        @error('tarif_per_jam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('admin.tarif.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#1e3a8a;"><i class="bi bi-info-circle me-2"></i>Panduan Tarif</div>
                <p class="text-muted mb-3" style="font-size:.875rem;">Tarif dihitung per jam dan dibulatkan ke atas. Minimum 1 jam.</p>
                <div class="d-flex flex-column gap-2">
                    <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background:#fff;border:1px solid #bfdbfe;">
                        <div>
                            <div class="fw-semibold" style="color:#1e3a8a;">Motor</div>
                            <div class="text-muted" style="font-size:.8rem;">Sepeda motor roda dua</div>
                        </div>
                        <i class="bi bi-bicycle" style="font-size:1.5rem;color:#93c5fd;"></i>
                    </div>
                    <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background:#fff;border:1px solid #bfdbfe;">
                        <div>
                            <div class="fw-semibold" style="color:#1e3a8a;">Mobil</div>
                            <div class="text-muted" style="font-size:.8rem;">Kendaraan roda empat</div>
                        </div>
                        <i class="bi bi-car-front" style="font-size:1.5rem;color:#93c5fd;"></i>
                    </div>
                    <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background:#fff;border:1px solid #bfdbfe;">
                        <div>
                            <div class="fw-semibold" style="color:#1e3a8a;">Lainnya</div>
                            <div class="text-muted" style="font-size:.8rem;">Truk, bus, kendaraan besar</div>
                        </div>
                        <i class="bi bi-truck" style="font-size:1.5rem;color:#93c5fd;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
