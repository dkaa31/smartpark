@extends('layouts.app')
@section('title','Struk Parkir')
@section('breadcrumb') Transaksi / <span>Struk</span> @endsection

@section('sidebar-menu')
    <a href="{{ route('petugas.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="{{ route('petugas.transaksi.index') }}" class="sidebar-link active"><i class="bi bi-arrow-left-right"></i> Transaksi Parkir</a>
    <a href="{{ route('petugas.transaksi.keluar.form') }}" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> Kendaraan Keluar</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('petugas.transaksi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-bold mb-0">Struk Parkir</h5>
</div>

<div class="row g-4">
    {{-- Kiri: Struk --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header" style="font-size:.75rem;letter-spacing:1px;text-transform:uppercase;color:#6b7280;">
                Struk Pembayaran
            </div>
            <div class="card-body p-4">
                <div style="font-family:monospace;max-width:280px;margin:0 auto;">
                    <div class="text-center mb-3">
                        <div class="fw-bold fs-6">SMARTPARK</div>
                        <div style="font-size:.75rem;color:#6b7280;">Sistem Manajemen Parkir</div>
                    </div>
                    <hr>
                    <table class="w-100" style="font-size:.85rem;">
                        <tr><td>PLAT</td><td class="text-end fw-bold">{{ $transaksi->kendaraan->plat_nomor }}</td></tr>
                        <tr><td>JENIS</td><td class="text-end text-uppercase">{{ $transaksi->kendaraan->jenis_kendaraan }}</td></tr>
                        <tr><td>AREA</td><td class="text-end">{{ $transaksi->area->nama_area }}</td></tr>
                        <tr><td>MASUK</td><td class="text-end">{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</td></tr>
                        @if($transaksi->status === 'keluar')
                        <tr><td>KELUAR</td><td class="text-end">{{ $transaksi->waktu_keluar->format('d/m/Y H:i') }}</td></tr>
                        <tr><td>DURASI</td><td class="text-end">{{ $transaksi->durasi_jam }} jam</td></tr>
                        <tr><td>TARIF/JAM</td><td class="text-end">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</td></tr>
                        @endif
                    </table>
                    @if($transaksi->status === 'keluar')
                    <hr>
                    <div class="d-flex justify-content-between fw-bold" style="font-size:.95rem;">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span>
                    </div>
                    @if($jumlahBayar !== null)
                    <div class="d-flex justify-content-between mt-1" style="font-size:.85rem;">
                        <span>BAYAR</span>
                        <span>Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold mt-1" style="font-size:.95rem;color:#166534;">
                        <span>KEMBALI</span>
                        <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @else
                    <hr>
                    <div class="text-center my-3">
                        <div style="font-size:.65rem;color:#9ca3af;margin-bottom:6px;letter-spacing:.5px;text-transform:uppercase;">Scan untuk Keluar</div>
                        {{-- SVG inline, tidak butuh GD --}}
                        <div style="width:160px;height:160px;margin:0 auto;background:#fff;padding:4px;border:1px solid #e5e7eb;border-radius:6px;">
                            {!! $qrCode !!}
                        </div>
                        <div style="font-size:.75rem;color:#374151;font-weight:700;margin-top:8px;letter-spacing:2px;">
                            {{ $transaksi->kendaraan->plat_nomor }}
                        </div>
                    </div>
                    <hr>
                    <div class="text-center" style="font-size:.7rem;color:#9ca3af;">
                        Simpan tiket ini dengan baik.<br>Scan QR atau tunjukkan plat saat keluar.
                    </div>
                    <div class="text-center mt-2" style="font-size:.7rem;color:#9ca3af;">
                        ID: TRX-{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('petugas.transaksi.pdf', $transaksi->id) }}" class="btn btn-primary flex-fill">
                <i class="bi bi-printer me-2"></i>Cetak PDF
            </a>
            <a href="{{ route('petugas.dashboard') }}" class="btn btn-outline-secondary flex-fill">
                <i class="bi bi-plus me-2"></i>Transaksi Baru
            </a>
        </div>
    </div>

    {{-- Kanan: Detail & Kembalian --}}
    <div class="col-lg-7">
        @if($kembalian !== null)
        <div class="card mb-3" style="background:#f0fdf4;border-color:#bbf7d0;">
            <div class="card-body p-4">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div style="font-size:.7rem;color:#166534;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Biaya</div>
                        <div style="font-size:1.1rem;font-weight:700;color:#166534;">Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.7rem;color:#166534;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Dibayar</div>
                        <div style="font-size:1.1rem;font-weight:700;color:#166534;">Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:.7rem;color:#166534;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Kembalian</div>
                        <div style="font-size:1.5rem;font-weight:700;color:#166534;">Rp {{ number_format($kembalian, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">Detail Transaksi</div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:.9rem;">
                    <dt class="col-sm-5 text-muted">ID Transaksi</dt>
                    <dd class="col-sm-7">TRX-{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</dd>

                    <dt class="col-sm-5 text-muted">Plat Nomor</dt>
                    <dd class="col-sm-7 fw-bold">{{ $transaksi->kendaraan->plat_nomor }}</dd>

                    <dt class="col-sm-5 text-muted">Jenis Kendaraan</dt>
                    <dd class="col-sm-7 text-capitalize">{{ $transaksi->kendaraan->jenis_kendaraan }}</dd>

                    <dt class="col-sm-5 text-muted">Area Parkir</dt>
                    <dd class="col-sm-7">{{ $transaksi->area->nama_area }}</dd>

                    <dt class="col-sm-5 text-muted">Waktu Masuk</dt>
                    <dd class="col-sm-7">{{ $transaksi->waktu_masuk->format('d M Y, H:i:s') }}</dd>

                    @if($transaksi->status === 'keluar')
                    <dt class="col-sm-5 text-muted">Waktu Keluar</dt>
                    <dd class="col-sm-7">{{ $transaksi->waktu_keluar->format('d M Y, H:i:s') }}</dd>

                    <dt class="col-sm-5 text-muted">Durasi</dt>
                    <dd class="col-sm-7">{{ $transaksi->durasi_jam }} jam</dd>

                    <dt class="col-sm-5 text-muted">Tarif per Jam</dt>
                    <dd class="col-sm-7">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</dd>

                    <dt class="col-sm-5 text-muted">Biaya Total</dt>
                    <dd class="col-sm-7 fw-bold" style="color:#1e3a8a;font-size:1rem;">
                        Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}
                    </dd>

                    @if($jumlahBayar !== null)
                    <dt class="col-sm-5 text-muted">Jumlah Dibayar</dt>
                    <dd class="col-sm-7">Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</dd>

                    <dt class="col-sm-5 text-muted">Kembalian</dt>
                    <dd class="col-sm-7 fw-bold" style="color:#166534;">
                        Rp {{ number_format($kembalian, 0, ',', '.') }}
                    </dd>
                    @endif
                    @endif

                    <dt class="col-sm-5 text-muted">Status</dt>
                    <dd class="col-sm-7">
                        @if($transaksi->status === 'masuk')
                            <span class="badge badge-masuk px-2 py-1 rounded-pill">Masuk</span>
                        @else
                            <span class="badge badge-keluar px-2 py-1 rounded-pill">Keluar</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 text-muted">Petugas</dt>
                    <dd class="col-sm-7">{{ $transaksi->user->nama_lengkap }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
