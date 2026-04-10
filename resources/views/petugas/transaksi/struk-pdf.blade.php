<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; padding: 10px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        hr { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        .total { font-size: 13px; font-weight: bold; }
        .kembalian { font-size: 13px; font-weight: bold; color: #166534; }
    </style>
</head>
<body>
    <div class="center bold" style="font-size:14px;">SMARTPARK</div>
    <div class="center" style="font-size:10px;">Sistem Manajemen Parkir</div>
    <hr>
    <div class="row"><span>PLAT</span><span class="bold">{{ $transaksi->kendaraan->plat_nomor }}</span></div>
    <div class="row"><span>JENIS</span><span>{{ strtoupper($transaksi->kendaraan->jenis_kendaraan) }}</span></div>
    <div class="row"><span>AREA</span><span>{{ $transaksi->area->nama_area }}</span></div>
    <div class="row"><span>MASUK</span><span>{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</span></div>
    @if($transaksi->status === 'keluar')
    <div class="row"><span>KELUAR</span><span>{{ $transaksi->waktu_keluar->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>DURASI</span><span>{{ $transaksi->durasi_jam }} jam</span></div>
    <div class="row"><span>TARIF/JAM</span><span>Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</span></div>
    <hr>
    <div class="row total"><span>TOTAL</span><span>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span></div>
    @if($jumlahBayar !== null)
    <div class="row"><span>BAYAR</span><span>Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</span></div>
    <div class="row kembalian"><span>KEMBALI</span><span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span></div>
    @endif
    @else
    <hr>
    <div class="center" style="font-size:10px;">Simpan tiket ini dengan baik.</div>
    <div class="center" style="font-size:10px;">Scan QR atau tunjukkan plat saat keluar.</div>
    <div style="text-align:center;margin:8px 0;">
        <img src="{{ $qrDataUri }}" style="width:80px;height:80px;" alt="QR">
    </div>
    <div class="center" style="font-size:9px;color:#666;">{{ $transaksi->kendaraan->plat_nomor }}</div>
    @endif
    <hr>
    <div class="center" style="font-size:9px;">ID: TRX-{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</div>
    <div class="center" style="font-size:9px;">{{ now()->format('d/m/Y H:i:s') }}</div>
    <div class="center" style="font-size:9px;">Terima kasih</div>
</body>
</html>
