@extends('layouts.app')
@section('title','Edit Kendaraan')
@section('breadcrumb') Kelola / <a href="{{ route('admin.kendaraan.index') }}" style="color:inherit;">Kendaraan</a> / <span>Edit</span> @endsection

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
    <h5 class="fw-bold mb-0">Edit Kendaraan</h5>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2" style="color:#1e3a8a;"></i>Ubah Data Kendaraan</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.kendaraan.update', $kendaraan) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control"
                            value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}"
                            style="text-transform:uppercase;letter-spacing:1px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select" required>
                            <option value="motor"   {{ old('jenis_kendaraan',$kendaraan->jenis_kendaraan)=='motor'   ? 'selected':'' }}>Motor</option>
                            <option value="mobil"   {{ old('jenis_kendaraan',$kendaraan->jenis_kendaraan)=='mobil'   ? 'selected':'' }}>Mobil</option>
                            <option value="lainnya" {{ old('jenis_kendaraan',$kendaraan->jenis_kendaraan)=='lainnya' ? 'selected':'' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Warna</label>
                        <input type="text" name="warna" class="form-control" value="{{ old('warna', $kendaraan->warna) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control" value="{{ old('pemilik', $kendaraan->pemilik) }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tautkan ke User <small class="text-muted fw-normal">(opsional)</small></label>
                        <select name="id_user" class="form-select">
                            <option value="">-- Tidak Ditautkan --</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('id_user',$kendaraan->id_user)==$u->id ? 'selected':'' }}>
                                {{ $u->nama_lengkap }} ({{ $u->username }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                        <a href="{{ route('admin.kendaraan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="background:#f8fafc;border-color:#e5e7eb;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#374151;"><i class="bi bi-car-front me-2"></i>Info Kendaraan Saat Ini</div>
                <dl class="row mb-0" style="font-size:.875rem;">
                    <dt class="col-5 text-muted">Plat Nomor</dt>
                    <dd class="col-7 fw-bold" style="letter-spacing:1px;">{{ $kendaraan->plat_nomor }}</dd>
                    <dt class="col-5 text-muted">Jenis</dt>
                    <dd class="col-7 text-capitalize">{{ $kendaraan->jenis_kendaraan }}</dd>
                    <dt class="col-5 text-muted">Warna</dt>
                    <dd class="col-7">{{ $kendaraan->warna }}</dd>
                    <dt class="col-5 text-muted">Pemilik</dt>
                    <dd class="col-7">{{ $kendaraan->pemilik }}</dd>
                    <dt class="col-5 text-muted">User Terkait</dt>
                    <dd class="col-7">{{ $kendaraan->user?->nama_lengkap ?? '-' }}</dd>
                    <dt class="col-5 text-muted">Total Transaksi</dt>
                    <dd class="col-7">{{ $kendaraan->transaksi()->count() }} kali</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
