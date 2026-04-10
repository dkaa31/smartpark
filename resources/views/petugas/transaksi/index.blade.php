@extends('layouts.app')
@section('title','Transaksi Parkir')
@section('breadcrumb') <span>Transaksi Parkir</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('petugas.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-link active"><i class="bi bi-arrow-left-right"></i> Transaksi Parkir</a>
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> Kendaraan Keluar</a>
@endsection

@section('content')
<h5 class="fw-bold mb-3">Kendaraan Sedang Parkir</h5>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('petugas.transaksi.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="flex-grow-1" style="min-width:200px;">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Cari Plat</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nomor plat kendaraan..."
                        value="{{ request('search') }}" style="text-transform:uppercase;">
                </div>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Area</label>
                <select name="id_area" class="form-select" style="min-width:160px;">
                    <option value="">Semua Area</option>
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('id_area') == $area->id ? 'selected' : '' }}>
                        {{ $area->nama_area }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-3">Filter</button>
            @if(request()->hasAny(['search','id_area']))
            <a href="{{ route('petugas.transaksi.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
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
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Waktu Masuk</th>
                    <th>Durasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiMasuk as $i => $t)
                @php $durasi = max(1, ceil($t->waktu_masuk->diffInMinutes(now()) / 60)); @endphp
                <tr>
                    <td class="ps-4">{{ $transaksiMasuk->firstItem() + $i }}</td>
                    <td class="fw-bold">{{ $t->kendaraan->plat_nomor }}</td>
                    <td class="text-capitalize">{{ $t->kendaraan->jenis_kendaraan }}</td>
                    <td>{{ $t->area->nama_area }}</td>
                    <td style="font-size:.85rem;">{{ $t->waktu_masuk->format('d M Y, H:i') }}</td>
                    <td>
                        <span class="badge {{ $durasi >= 8 ? 'bg-danger' : ($durasi >= 4 ? 'bg-warning text-dark' : 'bg-light text-dark') }}"
                            style="font-size:.75rem;">
                            {{ $durasi }} jam
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('petugas.transaksi.keluar.form') }}?plat={{ $t->kendaraan->plat_nomor }}"
                            class="btn btn-sm btn-outline-danger me-1" title="Proses Keluar">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                        <a href="{{ route('petugas.transaksi.struk', $t->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Struk">
                            <i class="bi bi-receipt"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-car-front" style="font-size:1.5rem;display:block;opacity:.3;margin-bottom:6px;"></i>
                        Tidak ada kendaraan yang sedang parkir.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksiMasuk->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
        <span style="font-size:.8rem;color:#6b7280;">{{ $transaksiMasuk->total() }} kendaraan sedang parkir</span>
        {{ $transaksiMasuk->links() }}
    </div>
    @endif
</div>
@endsection
