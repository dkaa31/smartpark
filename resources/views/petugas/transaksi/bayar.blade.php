@extends('layouts.app')
@section('title','Pembayaran Parkir')
@section('breadcrumb') Transaksi / <span>Pembayaran</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('petugas.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-link"><i class="bi bi-arrow-left-right"></i> Transaksi Parkir</a>
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="sidebar-link active"><i class="bi bi-box-arrow-right"></i> Kendaraan Keluar</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Pembayaran Parkir</h5>
</div>

<div class="row g-4">
    {{-- Kiri: Form Pembayaran --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-cash-coin me-2" style="color:#1e3a8a;"></i>Input Pembayaran
            </div>
            <div class="card-body p-4">
                {{-- Info Kendaraan --}}
                <div class="p-3 rounded mb-4" style="background:#f8fafc;border:1px solid #e5e7eb;">
                    <div class="row g-2" style="font-size:.875rem;">
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Plat Nomor</div>
                            <div class="fw-bold" style="font-size:1rem;letter-spacing:.5px;">{{ $transaksi->kendaraan->plat_nomor }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Jenis</div>
                            <div class="fw-semibold text-capitalize">{{ $transaksi->kendaraan->jenis_kendaraan }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Waktu Masuk</div>
                            <div class="fw-semibold">{{ $transaksi->waktu_masuk->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Durasi</div>
                            <div class="fw-semibold">{{ $durasi }} jam</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Area</div>
                            <div class="fw-semibold">{{ $transaksi->area->nama_area }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:.75rem;">Tarif/Jam</div>
                            <div class="fw-semibold">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Total Biaya --}}
                <div class="p-3 rounded mb-4 text-center" style="background:#eff6ff;border:1px solid #bfdbfe;">
                    <div style="font-size:.75rem;color:#1e3a8a;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Biaya</div>
                    <div id="totalBiaya" style="font-size:2rem;font-weight:700;color:#1e3a8a;">
                        Rp {{ number_format($biaya, 0, ',', '.') }}
                    </div>
                    <div style="font-size:.72rem;color:#6b7280;margin-top:2px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Maks. Rp {{ number_format($capHarian, 0, ',', '.') }}/hari
                        ({{ $durasi }} jam parkir)
                    </div>
                </div>

                <form action="{{ route('petugas.transaksi.bayar', $transaksi->id) }}" method="POST" id="formBayar">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" id="jumlahBayar"
                            class="form-control form-control-lg @error('jumlah_bayar') is-invalid @enderror"
                            placeholder="Masukkan jumlah uang" min="{{ $biaya }}"
                            value="{{ old('jumlah_bayar') }}" required autofocus>
                        @error('jumlah_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Nominal Cepat --}}
                    <div class="mb-4">
                        <div class="text-muted mb-2" style="font-size:.8rem;">Nominal Cepat:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $nominals = [5000, 10000, 20000, 50000, 100000];
                            @endphp
                            @foreach($nominals as $nom)
                            <button type="button" class="btn btn-sm btn-outline-secondary nominal-btn"
                                data-value="{{ $nom }}">
                                Rp {{ number_format($nom, 0, ',', '.') }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kembalian Preview --}}
                    <div class="p-3 rounded mb-4" id="kembalianBox" style="background:#f0fdf4;border:1px solid #bbf7d0;display:none;">
                        <div style="font-size:.75rem;color:#166534;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Kembalian</div>
                        <div id="kembalianValue" style="font-size:1.75rem;font-weight:700;color:#166534;">Rp 0</div>
                    </div>

                    <div id="kurangBox" class="p-3 rounded mb-4" style="background:#fef2f2;border:1px solid #fecaca;display:none;">
                        <div style="font-size:.75rem;color:#991b1b;font-weight:600;">Uang kurang</div>
                        <div id="kurangValue" style="font-size:1.25rem;font-weight:700;color:#dc2626;">Rp 0</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-6" id="btnBayar" disabled>
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kanan: Preview Struk --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header" style="font-size:.75rem;letter-spacing:1px;text-transform:uppercase;color:#6b7280;">
                Pratinjau Struk
            </div>
            <div class="card-body p-4">
                <div style="font-family:monospace;max-width:280px;margin:0 auto;font-size:.85rem;">
                    <div class="text-center mb-2">
                        <div class="fw-bold" style="font-size:.95rem;">SMARTPARK</div>
                        <div style="font-size:.7rem;color:#6b7280;">Sistem Manajemen Parkir</div>
                    </div>
                    <hr style="border-color:#e5e7eb;">
                    <table class="w-100">
                        <tr><td>PLAT</td><td class="text-end fw-bold">{{ $transaksi->kendaraan->plat_nomor }}</td></tr>
                        <tr><td>JENIS</td><td class="text-end text-uppercase">{{ $transaksi->kendaraan->jenis_kendaraan }}</td></tr>
                        <tr><td>AREA</td><td class="text-end">{{ $transaksi->area->nama_area }}</td></tr>
                        <tr><td>MASUK</td><td class="text-end">{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</td></tr>
                        <tr><td>KELUAR</td><td class="text-end" id="waktuKeluarStruk">--/--/---- --:--</td></tr>
                        <tr><td>DURASI</td><td class="text-end">{{ $durasi }} jam</td></tr>
                        <tr><td>TARIF/JAM</td><td class="text-end">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</td></tr>
                    </table>
                    <hr style="border-color:#e5e7eb;">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($biaya, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between" id="struk-bayar" style="display:none!important;">
                        <span>BAYAR</span>
                        <span id="struk-bayar-val">-</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold" id="struk-kembalian" style="display:none!important;">
                        <span>KEMBALI</span>
                        <span id="struk-kembalian-val">-</span>
                    </div>
                    <hr style="border-color:#e5e7eb;">
                    <div class="text-center" style="font-size:.7rem;color:#9ca3af;">
                        ID: TRX-{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}<br>
                        Terima kasih telah menggunakan SmartPark
                    </div>
                </div>
            </div>
        </div>

        {{-- Shortcut info --}}
        <div class="card mt-3" style="background:#fffbeb;border-color:#fde68a;">
            <div class="card-body p-3">
                <div class="fw-semibold mb-1" style="font-size:.8rem;color:#92400e;"><i class="bi bi-lightbulb me-1"></i>Tips</div>
                <ul class="mb-0 ps-3" style="font-size:.8rem;color:#78350f;">
                    <li>Gunakan tombol nominal cepat untuk input lebih mudah</li>
                    <li>Kembalian dihitung otomatis saat mengetik</li>
                    <li>Struk akan tampil setelah pembayaran dikonfirmasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const biaya      = {{ $biaya }};
const inputBayar = document.getElementById('jumlahBayar');
const btnBayar   = document.getElementById('btnBayar');

// ── Real-time clock ──────────────────────────────────────
function updateClock() {
    const now = new Date();
    const dd  = String(now.getDate()).padStart(2,'0');
    const mo  = String(now.getMonth()+1).padStart(2,'0');
    const yy  = now.getFullYear();
    const hh  = String(now.getHours()).padStart(2,'0');
    const min = String(now.getMinutes()).padStart(2,'0');
    const el  = document.getElementById('waktuKeluarStruk');
    if (el) el.textContent = `${dd}/${mo}/${yy} ${hh}:${min}`;
}
updateClock();
setInterval(updateClock, 1000);
// ─────────────────────────────────────────────────────────

function formatRp(val) {
    return 'Rp ' + Math.floor(val).toLocaleString('id-ID');
}

function hitungKembalian() {
    const bayar = parseFloat(inputBayar.value) || 0;
    const kembalian = bayar - biaya;

    // Update struk preview
    const strukBayar     = document.getElementById('struk-bayar');
    const strukBayarVal  = document.getElementById('struk-bayar-val');
    const strukKembalian = document.getElementById('struk-kembalian');
    const strukKembalianVal = document.getElementById('struk-kembalian-val');

    if (bayar > 0) {
        strukBayar.style.display = 'flex';
        strukBayarVal.textContent = formatRp(bayar);
        strukKembalian.style.display = 'flex';
        strukKembalianVal.textContent = kembalian >= 0 ? formatRp(kembalian) : '-';
    } else {
        strukBayar.style.display = 'none';
        strukKembalian.style.display = 'none';
    }

    if (bayar >= biaya) {
        document.getElementById('kembalianBox').style.display = 'block';
        document.getElementById('kembalianValue').textContent = formatRp(kembalian);
        document.getElementById('kurangBox').style.display = 'none';
        btnBayar.disabled = false;
    } else if (bayar > 0) {
        document.getElementById('kembalianBox').style.display = 'none';
        document.getElementById('kurangBox').style.display = 'block';
        document.getElementById('kurangValue').textContent = formatRp(biaya - bayar);
        btnBayar.disabled = true;
    } else {
        document.getElementById('kembalianBox').style.display = 'none';
        document.getElementById('kurangBox').style.display = 'none';
        btnBayar.disabled = true;
    }
}

inputBayar.addEventListener('input', hitungKembalian);

// Nominal cepat
document.querySelectorAll('.nominal-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const current = parseFloat(inputBayar.value) || 0;
        inputBayar.value = current + parseFloat(this.dataset.value);
        hitungKembalian();
    });
});
</script>
@endpush
