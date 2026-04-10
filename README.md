# 🅿️ SmartPark — Sistem Manajemen Parkir Berbasis Web

Aplikasi manajemen parkir berbasis web yang dibangun dengan **Laravel 12**, **Bootstrap 5**, dan **MySQL**. Dirancang untuk memudahkan pengelolaan area parkir secara digital, mulai dari pencatatan kendaraan masuk/keluar, kalkulasi biaya otomatis, hingga laporan pendapatan.

---

## ✨ Fitur Utama

- **Multi-role** — Admin, Petugas, dan Owner dengan akses berbeda
- **Transaksi parkir** — Pencatatan masuk & keluar kendaraan secara real-time
- **QR Code tiket** — Generate QR code SVG tanpa perlu ekstensi GD
- **Cetak struk PDF** — Format thermal 80mm menggunakan DomPDF
- **Kalkulasi otomatis** — Biaya dan kembalian dihitung otomatis
- **Rekap laporan** — Rekap harian & bulanan untuk Owner, bisa download PDF
- **Log aktivitas** — Semua aksi penting tercatat otomatis
- **Filter & pencarian** — Tersedia di semua halaman data

---

## 👥 Role Pengguna

| Role | Akses |
|------|-------|
| **Admin** | Kelola user, tarif, area parkir, kendaraan, dan log aktivitas |
| **Petugas** | Proses kendaraan masuk/keluar, pembayaran, cetak struk |
| **Owner** | Pantau dashboard pendapatan dan unduh laporan rekap |

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Bootstrap 5, Alpine.js, Vite
- **Database:** MySQL
- **Library:** DomPDF (cetak PDF), chillerlan/php-qrcode (QR code)

---

## ⚙️ Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18 & NPM
- MySQL >= 5.7

---

## 🚀 Cara Clone & Instalasi

### 1. Clone repository

```bash
git clone https://github.com/username/smartpark.git
cd smartpark
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Salin file environment

```bash
cp .env.example .env
```

### 4. Konfigurasi database di `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parkir
DB_USERNAME=root
DB_PASSWORD=
```

> Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi MySQL kamu. Pastikan database `parkir` sudah dibuat.

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

### 7. Install dependensi JavaScript & build assets

```bash
npm install
npm run build
```

> **Windows:** Jika muncul error `running scripts is disabled`, jalankan perintah ini di PowerShell sebagai Administrator terlebih dahulu:
> ```powershell
> Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned
> ```

### 8. Jalankan server

```bash
php artisan serve
```

Akses aplikasi di **http://localhost:8000**

---

## 🔑 Akun Default

Setelah menjalankan seeder, akun berikut tersedia:

| Username | Password | Role | Status |
|----------|----------|------|--------|
| `admin` | `admin123` | Admin | Aktif |
| `petugas` | `petugas123` | Petugas | Aktif |
| `owner` | `owner123` | Owner | Aktif |

> ⚠️ Ganti password default sebelum deploy ke production.

---

## 📁 Struktur Direktori Utama

```
smartpark/
├── app/
│   ├── Helpers/          # QrHelper (generate QR SVG)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/    # Dashboard, User, Tarif, Area, Kendaraan, Log
│   │   │   ├── Petugas/  # Dashboard, Transaksi
│   │   │   └── Owner/    # Dashboard, Rekap
│   │   └── Middleware/   # RoleMiddleware
│   └── Models/           # TbUser, TbKendaraan, TbTarif, TbAreaParkir, TbTransaksi, TbLogAktivitas
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── admin/
│   ├── petugas/
│   └── owner/
└── routes/web.php
```

---

## 📄 Lisensi

Project ini dibuat untuk keperluan portofolio.
