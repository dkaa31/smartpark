@extends('layouts.app')
@section('title','Kelola Tarif')
@section('breadcrumb') Kelola / <span>Tarif Parkir</span> @endsection

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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kelola Tarif Parkir</h5>
    <a href="{{ route('admin.tarif.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Tambah Tarif
    </a>
</div>

{{-- Info tarif progresif --}}
<div class="row g-3 mb-3">
    @foreach(config('tarif_parkir') as $jenis => $cfg)
    <div class="col-md-4">
        <div class="card" style="border-left:3px solid #1e3a8a;">
            <div class="card-body p-3">
                <div class="fw-bold text-capitalize mb-2" style="color:#1e3a8a;font-size:.875rem;">
                    <i class="bi bi-{{ $jenis === 'motor' ? 'bicycle' : 'car-front' }} me-1"></i>{{ $jenis }}
                    <span class="badge ms-1" style="background:#eff6ff;color:#1e3a8a;font-size:.7rem;">Progresif</span>
                </div>
                <div style="font-size:.8rem;" class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jam pertama</span>
                        <span class="fw-semibold">Rp {{ number_format($cfg['tarif_jam1'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jam berikutnya</span>
                        <span class="fw-semibold">Rp {{ number_format($cfg['tarif_lanjut'], 0, ',', '.') }}/jam</span>
                    </div>
                    <div class="d-flex justify-content-between" style="border-top:1px solid #e5e7eb;padding-top:4px;margin-top:2px;">
                        <span class="text-muted">Maks. per hari</span>
                        <span class="fw-bold" style="color:#166534;">Rp {{ number_format($cfg['maks_harian'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-12">
        <div class="alert alert-info py-2 mb-0" style="font-size:.8rem;">
            <i class="bi bi-info-circle me-1"></i>
            Tarif progresif diatur di <code>config/tarif_parkir.php</code>.
            Kolom "Tarif per Jam" di tabel di bawah digunakan sebagai fallback untuk jenis <strong>Lainnya</strong>.
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.tarif.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Jenis Kendaraan</label>
                <select name="jenis" class="form-select" style="min-width:160px;">
                    <option value="">Semua Jenis</option>
                    <option value="motor"   {{ request('jenis')=='motor'   ? 'selected':'' }}>Motor</option>
                    <option value="mobil"   {{ request('jenis')=='mobil'   ? 'selected':'' }}>Mobil</option>
                    <option value="lainnya" {{ request('jenis')=='lainnya' ? 'selected':'' }}>Lainnya</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->filled('jenis'))
            <a href="{{ route('admin.tarif.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
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
                    <th>Jenis Kendaraan</th>
                    <th>Tarif per Jam</th>
                    <th>Contoh 3 Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarif as $i => $t)
                <tr>
                    <td class="ps-4">{{ $i + 1 }}</td>
                    <td>
                        <span class="text-capitalize fw-semibold">{{ $t->jenis_kendaraan }}</span>
                        @if($t->jenis_kendaraan === 'motor')
                            <i class="bi bi-bicycle ms-1 text-muted"></i>
                        @elseif($t->jenis_kendaraan === 'mobil')
                            <i class="bi bi-car-front ms-1 text-muted"></i>
                        @else
                            <i class="bi bi-truck ms-1 text-muted"></i>
                        @endif
                    </td>
                    <td class="fw-semibold" style="color:#1e3a8a;">Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</td>
                    <td class="text-muted" style="font-size:.85rem;">Rp {{ number_format($t->tarif_per_jam * 3, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.tarif.edit', $t) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.tarif.destroy', $t) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus tarif ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-search" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada tarif yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tarif->count())
    <div class="card-footer bg-white border-top" style="font-size:.8rem;color:#6b7280;">
        Menampilkan {{ $tarif->count() }} tarif
    </div>
    @endif
</div>
@endsection
