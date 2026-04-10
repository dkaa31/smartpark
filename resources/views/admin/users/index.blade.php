@extends('layouts.app')
@section('title','Kelola User')
@section('breadcrumb') Kelola / <span>User</span> @endsection

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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola User</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Tambah User
    </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:200px;">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau username..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Role</label>
                <select name="role" class="form-select" style="min-width:130px;">
                    <option value="">Semua Role</option>
                    <option value="admin"   {{ request('role')=='admin'   ? 'selected':'' }}>Admin</option>
                    <option value="petugas" {{ request('role')=='petugas' ? 'selected':'' }}>Petugas</option>
                    <option value="owner"   {{ request('role')=='owner'   ? 'selected':'' }}>Owner</option>
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Status</label>
                <select name="status" class="form-select" style="min-width:130px;">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')=='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->hasAny(['search','role','status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
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
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td class="ps-4">{{ $users->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $user->nama_lengkap }}</td>
                    <td>{{ $user->username }}</td>
                    <td><span class="text-capitalize">{{ $user->role }}</span></td>
                    <td>
                        @if($user->status_aktif === 'aktif')
                            <span class="badge badge-aktif px-2 py-1 rounded-pill" style="font-size:.75rem;">Aktif</span>
                        @else
                            <span class="badge badge-nonaktif px-2 py-1 rounded-pill" style="font-size:.75rem;">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada user yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <span style="font-size:.8rem;color:#6b7280;">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user</span>
        {{ $users->links('vendor.pagination.simple-custom') }}
    </div>
    @endif
</div>
@endsection
