@extends('layouts.app')
@section('title','Edit User')
@section('breadcrumb') Kelola / <a href="{{ route('admin.users.index') }}" style="color:inherit;">User</a> / <span>Edit</span> @endsection

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
    <h5 class="fw-bold mb-0">Edit User</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2" style="color:#1e3a8a;"></i>Ubah Data User</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin"   {{ old('role',$user->role)=='admin'   ? 'selected':'' }}>Admin</option>
                            <option value="petugas" {{ old('role',$user->role)=='petugas' ? 'selected':'' }}>Petugas</option>
                            <option value="owner"   {{ old('role',$user->role)=='owner'   ? 'selected':'' }}>Owner</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="aktif"    {{ old('status_aktif',$user->status_aktif)=='aktif'    ? 'selected':'' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_aktif',$user->status_aktif)=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#f8fafc;border-color:#e5e7eb;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#374151;"><i class="bi bi-person-circle me-2"></i>Info Akun Saat Ini</div>
                <dl class="row mb-0" style="font-size:.875rem;">
                    <dt class="col-5 text-muted">Nama</dt>
                    <dd class="col-7">{{ $user->nama_lengkap }}</dd>
                    <dt class="col-5 text-muted">Username</dt>
                    <dd class="col-7">{{ $user->username }}</dd>
                    <dt class="col-5 text-muted">Role</dt>
                    <dd class="col-7 text-capitalize">{{ $user->role }}</dd>
                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7">
                        @if($user->status_aktif === 'aktif')
                            <span class="badge badge-aktif px-2 py-1 rounded-pill" style="font-size:.75rem;">Aktif</span>
                        @else
                            <span class="badge badge-nonaktif px-2 py-1 rounded-pill" style="font-size:.75rem;">Nonaktif</span>
                        @endif
                    </dd>
                    <dt class="col-5 text-muted">Dibuat</dt>
                    <dd class="col-7">{{ $user->created_at->format('d M Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
