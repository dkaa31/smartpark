@extends('layouts.app')
@section('title','Kendaraan Keluar')
@section('breadcrumb') Transaksi / <span>Kendaraan Keluar</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('petugas.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-link"><i class="bi bi-arrow-left-right"></i> Transaksi Parkir</a>
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="sidebar-link active"><i class="bi bi-box-arrow-right"></i> Kendaraan Keluar</a>
@endsection

@section('content')
<h5 class="fw-bold mb-4">Proses Kendaraan Keluar</h5>

<div class="row g-4">
    {{-- Kiri: Form Cari Kendaraan --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-search me-2" style="color:#1e3a8a;"></i>Cari Kendaraan
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4" style="font-size:.875rem;">
                    Masukkan nomor plat kendaraan yang akan keluar. Sistem akan menghitung biaya parkir secara otomatis.
                </p>
                <form action="{{ route('petugas.transaksi.keluar') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nomor Plat Kendaraan</label>
                        <div class="input-group input-group-lg">
                            <input type="text" name="plat_nomor" id="platInput"
                                class="form-control @error('plat_nomor') is-invalid @enderror"
                                placeholder="Contoh: B 1234 XYZ"
                                value="{{ old('plat_nomor', request('plat')) }}"
                                style="text-transform:uppercase;letter-spacing:1px;" required autofocus>
                            <button type="button" class="btn btn-outline-secondary" id="btnScan" title="Scan QR Code">
                                <i class="bi bi-qr-code-scan"></i>
                            </button>
                        </div>
                        <div class="form-text">Ketik manual atau scan QR dari tiket parkir.</div>
                        @error('plat_nomor')<div class="text-danger mt-1" style="font-size:.85rem;">{{ $message }}</div>@enderror
                    </div>

                    {{-- Area Scanner --}}
                    <div id="scannerArea" style="display:none;" class="mb-4">
                        <div class="p-3 rounded" style="background:#f8fafc;border:1px solid #e5e7eb;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold" style="font-size:.875rem;color:#1e3a8a;">
                                    <i class="bi bi-camera me-1"></i>Kamera Aktif
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnStopScan">
                                    <i class="bi bi-x me-1"></i>Tutup
                                </button>
                            </div>
                            <div id="qr-reader" style="width:100%;min-height:300px;border-radius:8px;overflow:hidden;background:#000;"></div>
                            <div id="scanStatus" class="mt-2 text-center" style="font-size:.8rem;">
                                <span class="text-muted">Arahkan kamera ke QR code pada tiket parkir</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-6">
                        <i class="bi bi-search me-2"></i>Cari & Hitung Biaya
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kanan: Panduan --}}
    <div class="col-lg-6">
        <div class="card mb-3" style="background:#eff6ff;border-color:#bfdbfe;">
            <div class="card-body p-4">
                <div class="fw-bold mb-3" style="color:#1e3a8a;"><i class="bi bi-info-circle me-2"></i>Alur Proses Keluar</div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3 align-items-start">
                        <div style="width:28px;height:28px;background:#1e3a8a;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;">1</div>
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem;">Masukkan Plat Nomor</div>
                            <div class="text-muted" style="font-size:.8rem;">Ketik plat nomor kendaraan yang akan keluar</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div style="width:28px;height:28px;background:#1e3a8a;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;">2</div>
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem;">Hitung Biaya Otomatis</div>
                            <div class="text-muted" style="font-size:.8rem;">Sistem menghitung durasi dan biaya parkir</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div style="width:28px;height:28px;background:#1e3a8a;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;">3</div>
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem;">Input Pembayaran</div>
                            <div class="text-muted" style="font-size:.8rem;">Masukkan jumlah uang yang diterima, kembalian dihitung otomatis</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div style="width:28px;height:28px;background:#1e3a8a;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;">4</div>
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem;">Cetak Struk</div>
                            <div class="text-muted" style="font-size:.8rem;">Struk dengan detail pembayaran dan kembalian siap dicetak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="background:#f0fdf4;border-color:#bbf7d0;">
            <div class="card-body p-3">
                <div class="fw-semibold mb-1" style="font-size:.8rem;color:#166534;"><i class="bi bi-lightbulb me-1"></i>Info Tarif</div>
                <div style="font-size:.8rem;color:#166534;">
                    Biaya dihitung per jam (dibulatkan ke atas). Minimum 1 jam.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;
let scannerRunning = false;

document.getElementById('btnScan').addEventListener('click', function () {
    document.getElementById('scannerArea').style.display = 'block';
    document.getElementById('scanStatus').innerHTML = '<span class="text-muted">Memulai kamera...</span>';
    this.disabled = true;

    html5QrCode = new Html5Qrcode('qr-reader');

    const config = {
        fps: 15,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        disableFlip: false,
        experimentalFeatures: {
            useBarCodeDetectorIfSupported: true
        }
    };

    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras || cameras.length === 0) {
            document.getElementById('scanStatus').innerHTML =
                '<span style="color:#dc2626;">Tidak ada kamera ditemukan.</span>';
            document.getElementById('btnScan').disabled = false;
            return;
        }

        // Pilih kamera belakang jika ada
        let cameraId = cameras[cameras.length - 1].id;
        for (let cam of cameras) {
            if (cam.label && cam.label.toLowerCase().includes('back')) {
                cameraId = cam.id;
                break;
            }
        }

        html5QrCode.start(
            cameraId,
            config,
            function (decodedText) {
                const plat = decodedText.trim().toUpperCase();
                document.getElementById('platInput').value = plat;
                document.getElementById('scanStatus').innerHTML =
                    '<span style="color:#166534;font-weight:600;"><i class="bi bi-check-circle me-1"></i>Terbaca: ' + plat + ' — memproses...</span>';

                stopScanner();

                setTimeout(() => {
                    document.getElementById('platInput').closest('form').submit();
                }, 600);
            },
            function (errorMsg) {
                // scan frame error — abaikan, ini normal
            }
        ).then(() => {
            scannerRunning = true;
            document.getElementById('scanStatus').innerHTML =
                '<span class="text-muted">Arahkan kamera ke QR code pada tiket parkir</span>';
        }).catch(err => {
            document.getElementById('scanStatus').innerHTML =
                '<span style="color:#dc2626;">Gagal akses kamera: ' + err + '</span>';
            document.getElementById('btnScan').disabled = false;
        });

    }).catch(err => {
        document.getElementById('scanStatus').innerHTML =
            '<span style="color:#dc2626;">Izin kamera ditolak atau tidak tersedia.</span>';
        document.getElementById('btnScan').disabled = false;
    });
});

document.getElementById('btnStopScan').addEventListener('click', stopScanner);

function stopScanner() {
    if (html5QrCode && scannerRunning) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            html5QrCode = null;
            scannerRunning = false;
        }).catch(() => {
            html5QrCode = null;
            scannerRunning = false;
        });
    }
    document.getElementById('scannerArea').style.display = 'none';
    document.getElementById('btnScan').disabled = false;
    document.getElementById('scanStatus').innerHTML =
        '<span class="text-muted">Arahkan kamera ke QR code pada tiket parkir</span>';
}
</script>
@endpush
