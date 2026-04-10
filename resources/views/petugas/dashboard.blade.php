@extends('layouts.app')
@section('title','Dashboard Petugas')
@section('breadcrumb') <span>Panel Transaksi</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('petugas.dashboard') }}" class="sidebar-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-link {{ request()->routeIs('petugas.transaksi.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right"></i> Transaksi Parkir
    </a>
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="sidebar-link">
        <i class="bi bi-box-arrow-right"></i> Kendaraan Keluar
    </a>
@endsection

@section('content')
<div class="row g-4">
    {{-- Form Kendaraan Masuk --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-car-front me-2" style="color:#1e3a8a;"></i>Catat Kendaraan Masuk
            </div>
            <div class="card-body p-4">
                <form action="{{ route('petugas.transaksi.masuk') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Plat Kendaraan</label>
                        <input type="text" name="plat_nomor" class="form-control form-control-lg"
                            placeholder="Contoh: B 1234 XYZ" value="{{ old('plat_nomor') }}"
                            style="text-transform:uppercase;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Zona Parkir</label>
                        <select name="id_area" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Area --</option>
                            @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('id_area')==$area->id ? 'selected':'' }}
                                {{ $area->terisi >= $area->kapasitas ? 'disabled' : '' }}>
                                {{ $area->nama_area }}
                                ({{ $area->terisi }}/{{ $area->kapasitas }})
                                {{ $area->terisi >= $area->kapasitas ? '- PENUH' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="motor"   {{ old('jenis_kendaraan')=='motor'   ? 'selected':'' }}>Motor</option>
                            <option value="mobil"   {{ old('jenis_kendaraan')=='mobil'   ? 'selected':'' }}>Mobil</option>
                            <option value="lainnya" {{ old('jenis_kendaraan')=='lainnya' ? 'selected':'' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Waktu Masuk</label>
                        <input type="text" id="waktuMasukDisplay" class="form-control form-control-lg" readonly
                            style="background:#f9fafb;color:#6b7280;">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-6">
                        <i class="bi bi-save me-2"></i>Cetak & Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="col-lg-5">
        {{-- Preview Struk --}}
        <div class="card mb-3">
            <div class="card-header" style="font-size:.75rem;letter-spacing:1px;text-transform:uppercase;color:#6b7280;">
                Pratinjau Struk
            </div>
            <div class="card-body p-3">
                <div style="font-family:monospace;font-size:.8rem;background:#f9fafb;border-radius:8px;padding:16px;text-align:center;">
                    <div class="fw-bold" style="font-size:.9rem;">SMARTPARK</div>
                    <div style="color:#6b7280;font-size:.75rem;">Sistem Manajemen Parkir</div>
                    <hr style="border-color:#e5e7eb;margin:8px 0;">
                    <div class="d-flex justify-content-between"><span>PLAT:</span><span class="fw-bold">-</span></div>
                    <div class="d-flex justify-content-between"><span>JENIS:</span><span>-</span></div>
                    <div class="d-flex justify-content-between"><span>WAKTU:</span><span id="strukturWaktu" class="fw-bold">--:--:--</span></div>
                    <div class="d-flex justify-content-between"><span>ZONA:</span><span>-</span></div>
                    <hr style="border-color:#e5e7eb;margin:8px 0;">
                    <div style="color:#9ca3af;font-size:.7rem;">Simpan tiket ini dengan baik.<br>Tunjukkan saat keluar untuk pembayaran.</div>
                </div>
            </div>
        </div>

        {{-- Kapasitas --}}
        <div class="card mb-3" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-3">
                <div style="font-size:.7rem;font-weight:600;color:#1e3a8a;text-transform:uppercase;letter-spacing:.5px;">Kapasitas Terisi</div>
                <div style="font-size:2rem;font-weight:700;color:#1e3a8a;">{{ $persentase }}%</div>
            </div>
        </div>

        {{-- Total Shift --}}
        <div class="card" style="background:#f0fdf4;border-color:#bbf7d0;">
            <div class="card-body p-3">
                <div style="font-size:.7rem;font-weight:600;color:#166534;text-transform:uppercase;letter-spacing:.5px;">Total Shift Ini</div>
                <div style="font-size:2rem;font-weight:700;color:#166534;">{{ $totalShift }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const bulanId = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

function updateClock() {
    const now  = new Date();
    const dd   = String(now.getDate()).padStart(2, '0');
    const mm   = bulanId[now.getMonth()];
    const yyyy = now.getFullYear();
    const hh   = String(now.getHours()).padStart(2, '0');
    const min  = String(now.getMinutes()).padStart(2, '0');
    const ss   = String(now.getSeconds()).padStart(2, '0');

    const elDisplay = document.getElementById('waktuMasukDisplay');
    const elStruk   = document.getElementById('strukturWaktu');

    if (elDisplay) elDisplay.value     = `${dd} ${mm} ${yyyy} | ${hh}:${min}:${ss}`;
    if (elStruk)   elStruk.textContent = `${hh}:${min}:${ss}`;
}

updateClock();
setInterval(updateClock, 1000);
</script>
@endpush
