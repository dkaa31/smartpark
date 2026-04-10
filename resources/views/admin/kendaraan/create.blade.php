@extends('layouts.app')
@section('title','Tambah Kendaraan')
@section('breadcrumb') Kelola / <a href="{{ route('admin.kendaraan.index') }}" style="color:inherit;">Kendaraan</a> / <span>Tambah</span> @endsection

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
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Tambah Kendaraan</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-car-front me-2" style="color:#1e3a8a;"></i>Data Kendaraan Baru</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.kendaraan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control @error('plat_nomor') is-invalid @enderror"
                            value="{{ old('plat_nomor') }}" placeholder="Contoh: B 1234 ABC"
                            style="text-transform:uppercase;letter-spacing:1px;" required>
                        @error('plat_nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="motor"   {{ old('jenis_kendaraan')=='motor'   ? 'selected':'' }}>Motor</option>
                            <option value="mobil"   {{ old('jenis_kendaraan')=='mobil'   ? 'selected':'' }}>Mobil</option>
                            <option value="lainnya" {{ old('jenis_kendaraan')=='lainnya' ? 'selected':'' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna</label>
                        <input type="text" name="warna" class="form-control" value="{{ old('warna') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control" value="{{ old('pemilik') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tautkan ke User <small class="text-muted fw-normal">(opsional)</small></label>
                        <select name="id_user" class="form-select">
                            <option value="">-- Tidak Ditautkan --</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('id_user')==$u->id ? 'selected':'' }}>
                                {{ $u->nama_lengkap }} ({{ $u->username }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#1e3a8a;"><i class="bi bi-info-circle me-2"></i>Format Plat Nomor</div>
                <div class="d-flex flex-column gap-2" style="font-size:.875rem;">
                    <div class="p-2 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <span class="fw-bold" style="letter-spacing:1px;">B 1234 ABC</span>
                        <span class="text-muted ms-2">— Jakarta</span>
                    </div>
                    <div class="p-2 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <span class="fw-bold" style="letter-spacing:1px;">D 5678 XY</span>
                        <span class="text-muted ms-2">— Bandung</span>
                    </div>
                    <div class="p-2 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <span class="fw-bold" style="letter-spacing:1px;">AB 9012 CD</span>
                        <span class="text-muted ms-2">— Yogyakarta</span>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                    <div class="fw-semibold mb-1" style="font-size:.8rem;color:#1e3a8a;">Catatan</div>
                    <div class="text-muted" style="font-size:.8rem;">Kendaraan yang belum terdaftar akan otomatis dibuat saat transaksi masuk oleh petugas.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
