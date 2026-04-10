@extends('layouts.app')
@section('title','Tambah User')
@section('breadcrumb') Kelola / <a href="{{ route('admin.users.index') }}" style="color:inherit;">User</a> / <span>Tambah</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section-label">Kelola</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link active"><i class="bi bi-people"></i> Kelola User</a>
    <a href="{{ route('admin.tarif.index') }}" class="sidebar-link"><i class="bi bi-tag"></i> Kelola Tarif Parkir</a>
    <a href="{{ route('admin.area.index') }}" class="sidebar-link"><i class="bi bi-map"></i> Kelola Area Parkir</a>
    <a href="{{ route('admin.kendaraan.index') }}" class="sidebar-link"><i class="bi bi-car-front"></i> Kelola Kendaraan</a>
    <div class="nav-section-label">Laporan</div>
    <a href="{{ route('admin.log') }}" class="sidebar-link"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Tambah User</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-plus me-2" style="color:#1e3a8a;"></i>Data User Baru</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin"   {{ old('role')=='admin'   ? 'selected':'' }}>Admin</option>
                            <option value="petugas" {{ old('role')=='petugas' ? 'selected':'' }}>Petugas</option>
                            <option value="owner"   {{ old('role')=='owner'   ? 'selected':'' }}>Owner</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="aktif"    {{ old('status_aktif','aktif')=='aktif'    ? 'selected':'' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_aktif')=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#1e3a8a;"><i class="bi bi-info-circle me-2"></i>Panduan Role</div>
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <div class="fw-semibold" style="color:#1e3a8a;">Admin</div>
                        <div class="text-muted" style="font-size:.8rem;">Akses penuh: kelola user, tarif, area, kendaraan, dan log aktivitas.</div>
                    </div>
                    <div class="p-3 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <div class="fw-semibold" style="color:#1e3a8a;">Petugas</div>
                        <div class="text-muted" style="font-size:.8rem;">Mengelola transaksi parkir masuk & keluar, cetak struk.</div>
                    </div>
                    <div class="p-3 rounded" style="background:#fff;border:1px solid #bfdbfe;">
                        <div class="fw-semibold" style="color:#1e3a8a;">Owner</div>
                        <div class="text-muted" style="font-size:.8rem;">Melihat laporan dan rekap transaksi harian.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3" style="background:#fffbeb;border-color:#fde68a;">
            <div class="card-body p-3">
                <div class="fw-semibold mb-1" style="font-size:.8rem;color:#92400e;"><i class="bi bi-shield-lock me-1"></i>Keamanan</div>
                <div style="font-size:.8rem;color:#78350f;">Password minimal 6 karakter. Gunakan kombinasi huruf dan angka untuk keamanan lebih baik.</div>
            </div>
        </div>
    </div>
</div>
@endsection
