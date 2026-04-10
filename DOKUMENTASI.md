# Dokumentasi SmartPark - Sistem Manajemen Parkir Berbasis Web

**Stack Teknologi:** Laravel 12 + Bootstrap 5 + MySQL  
**Versi:** 1.0.0  
**Tanggal:** April 2026

---

## Daftar Isi

1. [Deskripsi Program](#1-deskripsi-program)
2. [ERD (Entity Relationship Diagram)](#2-erd-entity-relationship-diagram)
3. [Struktur Database](#3-struktur-database)
4. [Persyaratan Sistem](#4-persyaratan-sistem)
5. [Instalasi & Konfigurasi](#5-instalasi--konfigurasi)
6. [Akun Default](#6-akun-default)
7. [Struktur Direktori](#7-struktur-direktori)
8. [Fitur per Role](#8-fitur-per-role)
9. [Dokumentasi Fungsi/Prosedur](#9-dokumentasi-fungsiprosedur)
10. [Alur Penggunaan](#10-alur-penggunaan)
11. [Daftar Route](#11-daftar-route)
12. [Middleware & Keamanan](#12-middleware--keamanan)
13. [Desain UI](#13-desain-ui)
14. [Debugging](#14-debugging)
15. [Laporan Evaluasi Singkat](#15-laporan-evaluasi-singkat)

---

## 1. Deskripsi Program

SmartPark adalah sistem manajemen parkir berbasis web yang dibangun menggunakan framework Laravel 12, Bootstrap 5, dan MySQL. Sistem ini dirancang untuk memudahkan pengelolaan area parkir secara digital, mulai dari pencatatan kendaraan masuk/keluar, perhitungan biaya otomatis, hingga pelaporan pendapatan.

### Role Pengguna

Sistem memiliki **3 role** pengguna:

| Role | Deskripsi |
|------|-----------|
| **Admin** | Mengelola seluruh data master: user, tarif, area parkir, kendaraan, dan log aktivitas |
| **Petugas** | Menangani transaksi parkir harian: kendaraan masuk, keluar, pembayaran, dan cetak struk |
| **Owner** | Memantau dashboard pendapatan dan mengunduh rekap laporan |

### Fitur Utama

- **Multi-role login** dengan redirect otomatis sesuai role
- **CRUD data master**: User, Tarif, Area Parkir, Kendaraan
- **Transaksi parkir**: pencatatan masuk dan keluar kendaraan
- **Pembayaran & kembalian**: kalkulasi otomatis dengan tombol nominal cepat
- **QR code tiket**: generate QR code SVG tanpa GD extension
- **Cetak struk PDF**: format thermal 80mm menggunakan DomPDF
- **Rekap laporan**: rekap bulanan dan harian untuk Owner
- **Log aktivitas**: pencatatan otomatis semua aksi penting
- **Filter & pencarian**: tersedia di semua halaman data

---

## 2. ERD (Entity Relationship Diagram)

\+----------------+         +-------------------+
| tb_user        |         | tb_kendaraan      |
|----------------|         |-------------------|
| id (PK)        |1 ----- N| id (PK)           |
| nama_lengkap   |         | plat_nomor        |
| username       |         | jenis_kendaraan   |
| password       |         | warna             |
| role           |         | pemilik           |
| status_aktif   |         | id_user (FK)      |
+----------------+         +-------------------+
        |                           |
        |1                          |1
        |                           |
        N                           N
+--------------------+    +---------------------------+
| tb_log_aktivitas   |    | tb_transaksi              |
|--------------------|    |---------------------------|
| id (PK)            |    | id (PK)                   |
| id_user (FK)       |    | id_kendaraan (FK) --------+
| aktivitas          |    | waktu_masuk               |
| waktu_aktivitas    |    | waktu_keluar              |
+--------------------+    | id_tarif (FK) ------------+
                          | durasi_jam                |
                          | biaya_total               |
                          | status                    |
                          | id_user (FK)              |
                          | id_area (FK) -------------+
                          +---------------------------+
                                    |                  |
                                    |N                 |N
                                    |                  |
+----------------+ 1                |    +-------------------+ 1
| tb_tarif       |------------------+    | tb_area_parkir    |--+
|----------------|                       |-------------------|
| id (PK)        |                       | id (PK)           |
| jenis_kendaraan|                       | nama_area         |
| tarif_per_jam  |                       | kapasitas         |
+----------------+                       | terisi            |
                                         +-------------------+
\
### Relasi Antar Tabel

| Tabel Asal     | Kardinalitas | Tabel Tujuan     | Keterangan                                            |
|----------------|--------------|------------------|-------------------------------------------------------|
| tb_user        | 1 - N        | tb_kendaraan     | Satu user bisa memiliki banyak kendaraan              |
| tb_user        | 1 - N        | tb_transaksi     | Satu user (petugas) bisa menangani banyak transaksi   |
| tb_user        | 1 - N        | tb_log_aktivitas | Satu user bisa memiliki banyak log aktivitas          |
| tb_kendaraan   | 1 - N        | tb_transaksi     | Satu kendaraan bisa memiliki banyak riwayat transaksi |
| tb_tarif       | 1 - N        | tb_transaksi     | Satu tarif digunakan di banyak transaksi              |
| tb_area_parkir | 1 - N        | tb_transaksi     | Satu area parkir digunakan di banyak transaksi        |

---

## 3. Struktur Database

### Tabel tb_user

| Kolom        | Tipe Data                      | Keterangan                         |
|--------------|--------------------------------|------------------------------------|
| id           | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key                        |
| nama_lengkap | VARCHAR(100)                   | Nama lengkap pengguna              |
| username     | VARCHAR(50) UNIQUE             | Username untuk login               |
| password     | VARCHAR(255)                   | Password ter-hash (bcrypt)         |
| role         | ENUM(admin, petugas, owner)    | Role pengguna                      |
| status_aktif | TINYINT(1) DEFAULT 1           | Status aktif (1=aktif, 0=nonaktif) |
| created_at   | TIMESTAMP                      | Waktu dibuat                       |
| updated_at   | TIMESTAMP                      | Waktu diperbarui                   |

### Tabel tb_kendaraan

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key |
| plat_nomor | VARCHAR(20) UNIQUE | Nomor plat kendaraan |
| jenis_kendaraan | ENUM(motor, mobil) | Jenis kendaraan |
| warna | VARCHAR(50) | Warna kendaraan |
| pemilik | VARCHAR(100) | Nama pemilik kendaraan |
| id_user | BIGINT UNSIGNED NULLABLE | Foreign Key ke tb_user |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel tb_tarif

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key |
| jenis_kendaraan | ENUM(motor, mobil) | Jenis kendaraan |
| tarif_per_jam | DECIMAL(10,2) | Tarif per jam dalam rupiah |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel tb_area_parkir

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key |
| nama_area | VARCHAR(100) | Nama area parkir |
| kapasitas | INT | Total kapasitas slot parkir |
| terisi | INT DEFAULT 0 | Jumlah slot yang sedang terisi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel tb_transaksi

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key |
| id_kendaraan | BIGINT UNSIGNED | Foreign Key ke tb_kendaraan |
| waktu_masuk | DATETIME | Waktu kendaraan masuk |
| waktu_keluar | DATETIME NULLABLE | Waktu kendaraan keluar |
| id_tarif | BIGINT UNSIGNED | Foreign Key ke tb_tarif |
| durasi_jam | DECIMAL(5,2) NULLABLE | Durasi parkir dalam jam |
| biaya_total | DECIMAL(10,2) NULLABLE | Total biaya parkir |
| status | ENUM(parkir, selesai) DEFAULT parkir | Status transaksi |
| id_user | BIGINT UNSIGNED | Foreign Key ke tb_user (petugas) |
| id_area | BIGINT UNSIGNED | Foreign Key ke tb_area_parkir |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel tb_log_aktivitas

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | BIGINT UNSIGNED AUTO_INCREMENT | Primary Key |
| id_user | BIGINT UNSIGNED | Foreign Key ke tb_user |
| aktivitas | TEXT | Deskripsi aktivitas yang dilakukan |
| waktu_aktivitas | DATETIME | Waktu aktivitas terjadi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

---

## 4. Persyaratan Sistem

### Software yang Dibutuhkan

| Software | Versi Minimum | Keterangan                 |
|----------|---------------|----------------------------|
| PHP      | 8.2+          | Runtime utama Laravel      |
| Laravel  | 12.x          | Framework PHP              |
| MySQL    | 5.7+          | Database server            |
| Composer | 2.x           | Package manager PHP        |
| Node.js  | 18.x          | Build asset frontend        |
| NPM      | 9.x           | Package manager JavaScript |

### PHP Extensions yang Dibutuhkan

- ext-pdo - Koneksi database
- ext-mbstring - Manipulasi string multibyte
- ext-openssl - Enkripsi dan keamanan
- ext-tokenizer - Parsing PHP
- ext-xml - Pemrosesan XML
- ext-ctype - Pengecekan tipe karakter
- ext-json - Pemrosesan JSON
- ext-bcmath - Kalkulasi presisi tinggi
- ext-fileinfo - Deteksi tipe file
- ext-dom - Pemrosesan DOM (untuk DomPDF)

> **Catatan:** ext-gd tidak wajib karena QR code menggunakan output SVG.

---

## 5. Instalasi & Konfigurasi

### Langkah-langkah Instalasi

**1. Clone atau ekstrak project**
`ash
git clone https://github.com/username/smartpark.git
cd smartpark
`

**2. Install dependensi PHP**
`ash
composer install
`

**3. Install dependensi JavaScript**
`ash
npm install
`

**4. Salin file environment**
`ash
cp .env.example .env
`

**5. Konfigurasi file .env**
`env
APP_NAME=SmartPark
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartpark
DB_USERNAME=root
DB_PASSWORD=
`

**6. Generate application key**
`ash
php artisan key:generate
`

**7. Jalankan migrasi dan seeder**
`ash
php artisan migrate --seed
`

**8. Build asset frontend**
`ash
npm run build
`

**9. Jalankan development server**
`ash
php artisan serve
`

Akses aplikasi di http://localhost:8000

---

## 6. Akun Default

Akun berikut tersedia setelah menjalankan php artisan migrate --seed:

| Username | Password   | Role    | Status   |
|----------|------------|---------|----------|
| admin    | admin123   | Admin   | Aktif    |
| petugas  | petugas123 | Petugas | Aktif    |
| owner    | owner123   | Owner   | Aktif    |
| budi     | password   | Petugas | Aktif    |
| siti     | password   | Petugas | Nonaktif |

> **Penting:** Ganti password default sebelum deploy ke production.

---

## 7. Struktur Direktori

`
smartpark/
 app/
    Helpers/
       QrHelper.php                    # Helper generate QR code SVG
    Http/
       Controllers/
          Auth/
             LoginController.php     # Autentikasi login/logout
          Admin/
             DashboardAdminController.php
             UserController.php
             TarifController.php
             AreaParkirController.php
             KendaraanController.php
             LogAktivitasController.php
          Petugas/
             DashboardPetugasController.php
             TransaksiController.php
          Owner/
              DashboardOwnerController.php
              RekapController.php
       Middleware/
           RoleMiddleware.php          # Middleware cek role user
    Models/
        TbUser.php
        TbKendaraan.php
        TbTarif.php
        TbAreaParkir.php
        TbTransaksi.php
        TbLogAktivitas.php
 database/
    migrations/
       2024_01_01_000001_create_tb_user_table.php
       2024_01_01_000002_create_tb_tarif_table.php
       2024_01_01_000003_create_tb_area_parkir_table.php
       2024_01_01_000004_create_tb_kendaraan_table.php
       2024_01_01_000005_create_tb_transaksi_table.php
       2024_01_01_000006_create_tb_log_aktivitas_table.php
    seeders/
        DatabaseSeeder.php
        TbUserSeeder.php
        TbTarifSeeder.php
        TbAreaParkirSeeder.php
        TbKendaraanSeeder.php
        TbTransaksiSeeder.php
 resources/
    views/
        layouts/
           admin.blade.php
           petugas.blade.php
           owner.blade.php
        auth/
           login.blade.php
        admin/
           dashboard.blade.php
           users/
           tarif/
           area/
           kendaraan/
           log/
        petugas/
           dashboard.blade.php
           transaksi/
        owner/
            dashboard.blade.php
            rekap/
 routes/
     web.php
`

---

## 8. Fitur per Role

### Admin

| URL | Nama Fitur | Deskripsi |
|-----|-----------|-----------|
| /admin/dashboard | Dashboard Admin | Statistik total user, kendaraan, area parkir |
| /admin/users | Manajemen User | CRUD data pengguna sistem |
| /admin/users?search=&role=&status= | Filter User | Filter berdasarkan nama/username, role, status |
| /admin/tarif | Manajemen Tarif | CRUD tarif parkir per jenis kendaraan |
| /admin/area | Manajemen Area Parkir | CRUD area/zona parkir dengan kapasitas |
| /admin/kendaraan | Manajemen Kendaraan | CRUD data kendaraan terdaftar |
| /admin/log | Log Aktivitas | Lihat semua log aktivitas dengan filter |

### Petugas

| URL | Nama Fitur | Deskripsi |
|-----|-----------|-----------|
| /petugas/dashboard | Dashboard Petugas | Kapasitas terisi, total transaksi shift |
| /petugas/transaksi/masuk | Kendaraan Masuk | Form input kendaraan masuk, generate QR tiket |
| /petugas/transaksi | Daftar Transaksi | Tabel transaksi aktif dengan filter |
| /petugas/transaksi/{id}/keluar | Form Keluar | Scan QR atau input manual plat nomor |
| /petugas/transaksi/{id}/bayar | Form Bayar | Input nominal bayar, hitung kembalian |
| /petugas/transaksi/{id}/struk | Struk Transaksi | Tampilan struk setelah pembayaran |
| /petugas/transaksi/{id}/cetak-pdf | Cetak PDF | Download struk PDF format thermal 80mm |

### Owner

| URL | Nama Fitur | Deskripsi |
|-----|-----------|-----------|
| /owner/dashboard | Dashboard Owner | Pendapatan hari ini, bulan ini, rekap 7 hari |
| /owner/rekap | Rekap Bulanan | Rekap transaksi per bulan dengan total pendapatan |
| /owner/rekap?mode=harian&tanggal= | Rekap Harian | Rekap transaksi per jam dalam satu hari |
| /owner/rekap/download-pdf | Download PDF | Unduh laporan rekap dalam format PDF |

---

## 9. Dokumentasi Fungsi/Prosedur

### LoginController

**Namespace:** App\Http\Controllers\Auth\LoginController

#### showForm()
- **Parameter Input:** -
- **Proses Utama:** Menampilkan halaman form login
- **Output/Return:** View uth.login

#### login(Request )
- **Parameter Input:** username (string), password (string)
- **Proses Utama:**
  1. Validasi input username dan password
  2. Cek user di 	b_user berdasarkan username
  3. Verifikasi password dengan Hash::check()
  4. Cek status_aktif user (tolak jika nonaktif)
  5. Login user dengan Auth::login()
  6. Regenerate session untuk keamanan
  7. Catat log aktivitas login
  8. Redirect sesuai role (admin/petugas/owner)
- **Output/Return:** Redirect ke dashboard sesuai role, atau kembali dengan error

#### logout(Request )
- **Parameter Input:** -
- **Proses Utama:**
  1. Catat log aktivitas logout
  2. Auth::logout()
  3. Invalidate dan regenerate session token
- **Output/Return:** Redirect ke halaman login

---

### UserController

**Namespace:** App\Http\Controllers\Admin\UserController

#### index(Request )
- **Parameter Input:** search (string, opsional), 
ole (string, opsional), status (string, opsional)
- **Proses Utama:**
  1. Query TbUser dengan filter dinamis
  2. Filter search pada kolom 
ama_lengkap dan username
  3. Filter 
ole jika dipilih
  4. Filter status_aktif jika dipilih
  5. Paginate hasil 10 per halaman
- **Output/Return:** View dmin.users.index dengan data user

#### store(Request )
- **Parameter Input:** 
ama_lengkap, username, password, 
ole, status_aktif
- **Proses Utama:**
  1. Validasi input (username unique)
  2. Hash password dengan crypt()
  3. Simpan ke 	b_user
  4. Catat log aktivitas
- **Output/Return:** Redirect ke index dengan pesan sukses

#### update(Request , )
- **Parameter Input:** 
ama_lengkap, username, password (opsional), 
ole, status_aktif
- **Proses Utama:**
  1. Validasi input (username unique kecuali diri sendiri)
  2. Update data user
  3. Hash password baru jika diisi
  4. Catat log aktivitas
- **Output/Return:** Redirect ke index dengan pesan sukses

#### destroy()
- **Parameter Input:** id user
- **Proses Utama:**
  1. Cek apakah user adalah diri sendiri (tidak boleh hapus diri sendiri)
  2. Hapus user dari database
  3. Catat log aktivitas
- **Output/Return:** Redirect ke index dengan pesan sukses

---

### TarifController

**Namespace:** App\Http\Controllers\Admin\TarifController

#### index()
- **Parameter Input:** -
- **Proses Utama:** Ambil semua data tarif
- **Output/Return:** View dmin.tarif.index

#### store(Request )
- **Parameter Input:** jenis_kendaraan, 	arif_per_jam
- **Proses Utama:** Validasi dan simpan tarif baru, catat log
- **Output/Return:** Redirect ke index

#### update(Request , )
- **Parameter Input:** jenis_kendaraan, 	arif_per_jam
- **Proses Utama:** Validasi dan update data tarif, catat log
- **Output/Return:** Redirect ke index

#### destroy()
- **Parameter Input:** id tarif
- **Proses Utama:** Hapus tarif, catat log
- **Output/Return:** Redirect ke index

---

### AreaParkirController

**Namespace:** App\Http\Controllers\Admin\AreaParkirController

#### index()
- **Parameter Input:** -
- **Proses Utama:** Ambil semua data area parkir
- **Output/Return:** View dmin.area.index

#### store(Request )
- **Parameter Input:** 
ama_area, kapasitas
- **Proses Utama:** Validasi dan simpan area baru dengan 	erisi = 0, catat log
- **Output/Return:** Redirect ke index

#### update(Request , )
- **Parameter Input:** 
ama_area, kapasitas
- **Proses Utama:** Validasi dan update data area, catat log
- **Output/Return:** Redirect ke index

#### destroy()
- **Parameter Input:** id area
- **Proses Utama:** Hapus area parkir, catat log
- **Output/Return:** Redirect ke index

---

### KendaraanController

**Namespace:** App\Http\Controllers\Admin\KendaraanController

#### index(Request )
- **Parameter Input:** search (string, opsional), jenis (string, opsional)
- **Proses Utama:**
  1. Query TbKendaraan dengan eager load relasi user
  2. Filter search pada plat_nomor dan pemilik
  3. Filter jenis_kendaraan jika dipilih
  4. Paginate 10 per halaman
- **Output/Return:** View dmin.kendaraan.index

#### store(Request )
- **Parameter Input:** plat_nomor, jenis_kendaraan, warna, pemilik, id_user (opsional)
- **Proses Utama:** Validasi (plat_nomor unique), simpan kendaraan, catat log
- **Output/Return:** Redirect ke index

#### update(Request , )
- **Parameter Input:** plat_nomor, jenis_kendaraan, warna, pemilik, id_user
- **Proses Utama:** Validasi, update data kendaraan, catat log
- **Output/Return:** Redirect ke index

#### destroy()
- **Parameter Input:** id kendaraan
- **Proses Utama:** Hapus kendaraan, catat log
- **Output/Return:** Redirect ke index

---

### LogAktivitasController

**Namespace:** App\Http\Controllers\Admin\LogAktivitasController

#### index(Request )
- **Parameter Input:** search (string, opsional), id_user (int, opsional), 	anggal (date, opsional)
- **Proses Utama:**
  1. Query TbLogAktivitas dengan eager load relasi user
  2. Filter search pada kolom ktivitas
  3. Filter berdasarkan id_user jika dipilih
  4. Filter berdasarkan tanggal pada waktu_aktivitas
  5. Order by waktu_aktivitas descending
  6. Paginate 15 per halaman
- **Output/Return:** View dmin.log.index dengan data log dan daftar user

---

### DashboardAdminController

**Namespace:** App\Http\Controllers\Admin\DashboardAdminController

#### index()
- **Parameter Input:** -
- **Proses Utama:**
  1. Hitung total user aktif
  2. Hitung total kendaraan terdaftar
  3. Hitung total area parkir
  4. Hitung total transaksi hari ini
- **Output/Return:** View dmin.dashboard dengan statistik

---

### DashboardPetugasController

**Namespace:** App\Http\Controllers\Petugas\DashboardPetugasController

#### index()
- **Parameter Input:** -
- **Proses Utama:**
  1. Ambil data semua area parkir beserta kapasitas dan terisi
  2. Hitung total transaksi shift hari ini oleh petugas yang login
  3. Hitung total kendaraan yang sedang parkir (status = parkir)
- **Output/Return:** View petugas.dashboard dengan data kapasitas dan statistik shift

---

### TransaksiController

**Namespace:** App\Http\Controllers\Petugas\TransaksiController

#### masuk(Request )
- **Parameter Input:** plat_nomor, id_area
- **Proses Utama:**
  1. Cari kendaraan berdasarkan plat_nomor
  2. Cek apakah kendaraan sudah dalam status parkir
  3. Ambil tarif sesuai jenis kendaraan
  4. Buat record transaksi baru dengan waktu_masuk = now(), status = parkir
  5. Increment 	erisi pada area parkir
  6. Generate QR code SVG via QrHelper::toDataUri()
  7. Catat log aktivitas
- **Output/Return:** View tiket parkir dengan QR code

#### ormKeluar()
- **Parameter Input:** -
- **Proses Utama:** Tampilkan form scan QR atau input plat nomor untuk proses keluar
- **Output/Return:** View petugas.transaksi.keluar

#### keluar(Request )
- **Parameter Input:** plat_nomor atau id_transaksi (dari scan QR)
- **Proses Utama:**
  1. Cari transaksi aktif berdasarkan plat nomor atau ID
  2. Hitung durasi parkir dari waktu_masuk hingga sekarang
  3. Hitung iaya_total berdasarkan durasi dan tarif per jam
  4. Tampilkan detail transaksi untuk konfirmasi
- **Output/Return:** View konfirmasi keluar dengan detail biaya

#### ormBayar()
- **Parameter Input:** id transaksi
- **Proses Utama:** Ambil data transaksi, tampilkan form input nominal pembayaran
- **Output/Return:** View petugas.transaksi.bayar

#### prosesBayar(Request , )
- **Parameter Input:** 
ominal_bayar
- **Proses Utama:**
  1. Validasi nominal bayar >= biaya total
  2. Hitung kembalian = nominal_bayar - biaya_total
  3. Update transaksi: waktu_keluar, durasi_jam, iaya_total, status = selesai
  4. Decrement 	erisi pada area parkir
  5. Simpan kembalian ke session flash
  6. Catat log aktivitas
- **Output/Return:** Redirect ke halaman struk

#### struk()
- **Parameter Input:** id transaksi
- **Proses Utama:**
  1. Ambil data transaksi lengkap dengan relasi
  2. Baca kembalian dari session flash
  3. Generate QR code SVG untuk struk
- **Output/Return:** View petugas.transaksi.struk

#### cetakPdf()
- **Parameter Input:** id transaksi
- **Proses Utama:**
  1. Ambil data transaksi
  2. Baca kembalian dari session flash
  3. Render view struk ke PDF menggunakan DomPDF
  4. Set ukuran kertas custom 80mm x 200mm
- **Output/Return:** Download file PDF struk parkir

---

### DashboardOwnerController

**Namespace:** App\Http\Controllers\Owner\DashboardOwnerController

#### index()
- **Parameter Input:** -
- **Proses Utama:**
  1. Hitung total pendapatan hari ini (transaksi status selesai, tanggal hari ini)
  2. Hitung total pendapatan bulan ini
  3. Ambil rekap pendapatan 7 hari terakhir (per hari)
  4. Hitung total transaksi selesai hari ini
- **Output/Return:** View owner.dashboard dengan data pendapatan dan grafik

---

### RekapController

**Namespace:** App\Http\Controllers\Owner\RekapController

#### index(Request )
- **Parameter Input:** mode (bulanan/harian), ulan (Y-m, opsional), 	anggal (Y-m-d, opsional)
- **Proses Utama:**
  1. Jika mode = bulanan: group transaksi per bulan, hitung total pendapatan dan jumlah transaksi
  2. Jika mode = harian: filter transaksi pada tanggal tertentu, group per jam
  3. Navigasi hari sebelum/sesudah untuk mode harian
- **Output/Return:** View owner.rekap.index dengan data rekap

#### downloadPdf(Request )
- **Parameter Input:** mode, ulan, 	anggal
- **Proses Utama:**
  1. Ambil data rekap sesuai mode dan parameter
  2. Render view rekap ke PDF menggunakan DomPDF
- **Output/Return:** Download file PDF laporan rekap

---

### QrHelper

**Namespace:** App\Helpers\QrHelper

#### 	oSvg(string ): string
- **Parameter Input:** data - string yang akan di-encode ke QR (misal: ID transaksi atau plat nomor)
- **Proses Utama:**
  1. Buat instance QROptions dengan outputInterface = QRMarkupSVG
  2. Set scale = 10, quietzoneSize = 4, eccLevel = EccLevel::M
  3. Generate QR code menggunakan library chillerlan/php-qrcode
  4. Return string SVG markup
- **Output/Return:** String SVG QR code

#### 	oDataUri(string ): string
- **Parameter Input:** data - string yang akan di-encode
- **Proses Utama:**
  1. Panggil 	oSvg() untuk mendapatkan SVG string
  2. Encode ke base64
  3. Bungkus dalam format data URI data:image/svg+xml;base64,...
- **Output/Return:** String data URI SVG untuk digunakan di atribut src tag <img>

---

## 10. Alur Penggunaan

### Alur Kendaraan Masuk

1. Petugas login ke sistem dengan username dan password
2. Petugas membuka menu **Kendaraan Masuk** di dashboard
3. Petugas memilih area parkir yang tersedia
4. Petugas memasukkan plat nomor kendaraan
5. Sistem mencari data kendaraan di database
6. Jika kendaraan belum terdaftar, petugas dapat mendaftarkan terlebih dahulu
7. Sistem mengecek apakah kendaraan sudah dalam status parkir
8. Sistem mengambil tarif sesuai jenis kendaraan (motor/mobil)
9. Sistem membuat record transaksi baru dengan waktu masuk saat ini
10. Sistem menambah jumlah 	erisi pada area parkir yang dipilih
11. Sistem menampilkan tiket parkir dengan **QR code** berisi ID transaksi
12. Petugas mencetak atau menunjukkan tiket kepada pengemudi

### Alur Kendaraan Keluar

1. Petugas membuka menu **Kendaraan Keluar**
2. Petugas memilih metode identifikasi:
   - **Scan QR**: Klik tombol scan, izinkan akses kamera, arahkan ke QR code tiket
   - **Input Manual**: Ketik plat nomor kendaraan
3. Sistem mencari transaksi aktif berdasarkan ID (dari QR) atau plat nomor
4. Sistem menghitung durasi parkir dari waktu masuk hingga sekarang
5. Sistem menghitung biaya total berdasarkan durasi x tarif per jam
6. Sistem menampilkan detail transaksi untuk konfirmasi
7. Petugas mengklik **Proses Keluar** untuk melanjutkan ke pembayaran
8. Petugas memasukkan nominal uang yang diterima dari pengemudi
9. Sistem menghitung kembalian secara otomatis (real-time)
10. Petugas dapat menggunakan tombol nominal cepat (Rp 5.000, 10.000, 20.000, dst.)
11. Petugas mengklik **Proses Bayar**
12. Sistem memperbarui transaksi: waktu keluar, durasi, biaya, status = selesai
13. Sistem mengurangi jumlah 	erisi pada area parkir
14. Sistem menampilkan struk transaksi dengan detail pembayaran dan kembalian
15. Petugas dapat mencetak struk PDF thermal 80mm untuk diberikan kepada pengemudi

### Alur Rekap Owner

1. Owner login ke sistem
2. Owner membuka menu **Rekap Transaksi**
3. Owner memilih mode tampilan:
   - **Bulanan**: Melihat rekap pendapatan per bulan
   - **Harian**: Melihat rekap transaksi per jam dalam satu hari tertentu
4. Untuk mode harian, owner dapat navigasi hari menggunakan tombol ** Sebelumnya** dan **Berikutnya **
5. Owner dapat mengunduh laporan dalam format PDF dengan tombol **Download PDF**
6. Owner juga dapat memantau dashboard untuk melihat:
   - Total pendapatan hari ini
   - Total pendapatan bulan ini
   - Grafik rekap pendapatan 7 hari terakhir

---

## 11. Daftar Route

### Route Autentikasi

| Method | URL | Nama Route | Middleware | Keterangan |
|--------|-----|-----------|-----------|------------|
| GET | /login | login | guest | Tampilkan form login |
| POST | /login | login.post | guest | Proses login |
| POST | /logout | logout | auth | Proses logout |

### Route Admin

| Method | URL | Nama Route | Middleware | Keterangan |
|--------|-----|-----------|-----------|------------|
| GET | /admin/dashboard | admin.dashboard | auth, role:admin | Dashboard admin |
| GET | /admin/users | admin.users.index | auth, role:admin | Daftar user |
| GET | /admin/users/create | admin.users.create | auth, role:admin | Form tambah user |
| POST | /admin/users | admin.users.store | auth, role:admin | Simpan user baru |
| GET | /admin/users/{id}/edit | admin.users.edit | auth, role:admin | Form edit user |
| PUT | /admin/users/{id} | admin.users.update | auth, role:admin | Update user |
| DELETE | /admin/users/{id} | admin.users.destroy | auth, role:admin | Hapus user |
| GET | /admin/tarif | admin.tarif.index | auth, role:admin | Daftar tarif |
| GET | /admin/tarif/create | admin.tarif.create | auth, role:admin | Form tambah tarif |
| POST | /admin/tarif | admin.tarif.store | auth, role:admin | Simpan tarif baru |
| GET | /admin/tarif/{id}/edit | admin.tarif.edit | auth, role:admin | Form edit tarif |
| PUT | /admin/tarif/{id} | admin.tarif.update | auth, role:admin | Update tarif |
| DELETE | /admin/tarif/{id} | admin.tarif.destroy | auth, role:admin | Hapus tarif |
| GET | /admin/area | admin.area.index | auth, role:admin | Daftar area parkir |
| GET | /admin/area/create | admin.area.create | auth, role:admin | Form tambah area |
| POST | /admin/area | admin.area.store | auth, role:admin | Simpan area baru |
| GET | /admin/area/{id}/edit | admin.area.edit | auth, role:admin | Form edit area |
| PUT | /admin/area/{id} | admin.area.update | auth, role:admin | Update area |
| DELETE | /admin/area/{id} | admin.area.destroy | auth, role:admin | Hapus area |
| GET | /admin/kendaraan | admin.kendaraan.index | auth, role:admin | Daftar kendaraan |
| GET | /admin/kendaraan/create | admin.kendaraan.create | auth, role:admin | Form tambah kendaraan |
| POST | /admin/kendaraan | admin.kendaraan.store | auth, role:admin | Simpan kendaraan |
| GET | /admin/kendaraan/{id}/edit | admin.kendaraan.edit | auth, role:admin | Form edit kendaraan |
| PUT | /admin/kendaraan/{id} | admin.kendaraan.update | auth, role:admin | Update kendaraan |
| DELETE | /admin/kendaraan/{id} | admin.kendaraan.destroy | auth, role:admin | Hapus kendaraan |
| GET | /admin/log | admin.log.index | auth, role:admin | Log aktivitas |

### Route Petugas

| Method | URL | Nama Route | Middleware | Keterangan |
|--------|-----|-----------|-----------|------------|
| GET | /petugas/dashboard | petugas.dashboard | auth, role:petugas | Dashboard petugas |
| GET | /petugas/transaksi | petugas.transaksi.index | auth, role:petugas | Daftar transaksi |
| GET | /petugas/transaksi/masuk | petugas.transaksi.masuk | auth, role:petugas | Form kendaraan masuk |
| POST | /petugas/transaksi/masuk | petugas.transaksi.masuk.post | auth, role:petugas | Proses kendaraan masuk |
| GET | /petugas/transaksi/keluar | petugas.transaksi.keluar | auth, role:petugas | Form kendaraan keluar |
| POST | /petugas/transaksi/keluar | petugas.transaksi.keluar.post | auth, role:petugas | Proses cari transaksi |
| GET | /petugas/transaksi/{id}/bayar | petugas.transaksi.bayar | auth, role:petugas | Form pembayaran |
| POST | /petugas/transaksi/{id}/bayar | petugas.transaksi.bayar.post | auth, role:petugas | Proses pembayaran |
| GET | /petugas/transaksi/{id}/struk | petugas.transaksi.struk | auth, role:petugas | Tampilan struk |
| GET | /petugas/transaksi/{id}/cetak-pdf | petugas.transaksi.cetak-pdf | auth, role:petugas | Download PDF struk |

### Route Owner

| Method | URL | Nama Route | Middleware | Keterangan |
|--------|-----|-----------|-----------|------------|
| GET | /owner/dashboard | owner.dashboard | auth, role:owner | Dashboard owner |
| GET | /owner/rekap | owner.rekap.index | auth, role:owner | Rekap transaksi |
| GET | /owner/rekap/download-pdf | owner.rekap.download-pdf | auth, role:owner | Download PDF rekap |

---

## 12. Middleware & Keamanan

### RoleMiddleware

File: `app/Http/Middleware/RoleMiddleware.php`

Middleware ini bertugas memastikan bahwa user yang mengakses suatu route memiliki role yang sesuai.

Middleware didaftarkan di `bootstrap/app.php` dengan alias `role` dan digunakan pada route group.

### Proteksi CSRF

Semua form POST, PUT, DELETE menggunakan token CSRF Laravel. Token CSRF diverifikasi otomatis oleh middleware VerifyCsrfToken bawaan Laravel.

### Enkripsi Password (Bcrypt)

Password user di-hash menggunakan bcrypt sebelum disimpan ke database dengan `bcrypt($request->password)`. Verifikasi password menggunakan `Hash::check()`.

### Session Regenerate

Setelah login berhasil, session di-regenerate untuk mencegah session fixation attack dengan `$request->session()->regenerate()`. Setelah logout, session di-invalidate dan token di-regenerate.

### Pengecekan Status Aktif

Setiap login, sistem mengecek `status_aktif` user sebelum mengizinkan masuk. Jika `status_aktif = 0`, login ditolak dengan pesan error.

---

## 13. Desain UI

### Palet Warna

| Nama | Kode Hex | Penggunaan |
|------|----------|-----------|
| Primary | #1e3a8a | Sidebar, header, tombol utama |
| Primary Light | #2563eb | Hover state, aksen |
| Background | #f0f2f5 | Background halaman utama |
| White | #ffffff | Card, konten utama |
| Success | #16a34a | Badge status aktif, tombol sukses |
| Warning | #d97706 | Badge durasi sedang, peringatan |
| Danger | #dc2626 | Badge durasi lama, tombol hapus |
| Text Primary | #1e293b | Teks utama |
| Text Secondary | #64748b | Teks sekunder, label |
| Border | #e2e8f0 | Border card, tabel |

### Layout Aplikasi

Aplikasi menggunakan layout **Sidebar + Topbar**:

- **Sidebar** (lebar 250px): Logo SmartPark, menu navigasi sesuai role, info user yang login
- **Topbar**: Judul halaman aktif, tombol logout
- **Content Area**: Area konten utama dengan padding dan card Bootstrap

### CDN yang Digunakan

| Library | Versi | Fungsi |
|---------|-------|--------|
| Bootstrap | 5.3.2 | Framework CSS dan komponen UI |
| Bootstrap Icons | 1.11.3 | Icon set untuk tombol dan menu |
| html5-qrcode | 2.3.8 | Scan QR code via kamera browser |

### Konfigurasi html5-qrcode

Konfigurasi scanner QR menggunakan getCameras() untuk memilih kamera belakang secara otomatis, dengan fps 10 dan qrbox 250x250 pixel.

---

## 14. Debugging

Berikut adalah daftar bug/error yang ditemukan selama pengembangan beserta solusinya:

### Bug 1: Error Undefined constant QRCode ECC_M

**Masalah:** Penggunaan konstanta QRCode::ECC_M yang sudah tidak valid di versi terbaru library chillerlan/php-qrcode.

**Solusi:** Ganti ke EccLevel::M dari class chillerlan\QRCode\Common\EccLevel.

Sebelum (error): \->eccLevel = QRCode::ECC_M;

Sesudah (benar): \->eccLevel = EccLevel::M;

---

### Bug 2: Error ext-gd not loaded

**Masalah:** Library QR code mencoba menggunakan output PNG yang membutuhkan PHP extension ext-gd, namun extension tersebut tidak aktif di server.

**Solusi:** Ganti output dari QRGdImagePNG ke QRMarkupSVG yang tidak membutuhkan GD extension.

Sebelum: \->outputInterface = QRGdImagePNG::class;

Sesudah: \->outputInterface = QRMarkupSVG::class;

---

### Bug 3: Error outputType key invalid

**Masalah:** Pada library chillerlan/php-qrcode versi 6, key konfigurasi outputType sudah diganti menjadi outputInterface.

**Solusi:** Ganti key konfigurasi di QROptions dari outputType ke outputInterface.

---

### Bug 4: QR Tidak Terbaca Scanner

**Masalah:** QR code yang dihasilkan terlalu kecil dan tidak memiliki quiet zone yang cukup sehingga scanner kamera kesulitan membacanya.

**Solusi:**
1. Perbesar scale dari 4 ke 10
2. Tambah quietzoneSize sebesar 4
3. Perbaiki konfigurasi html5-qrcode untuk menggunakan getCameras() dan memilih kamera belakang secara otomatis

---

### Bug 5: Session Kembalian Hilang Saat Cetak PDF

**Masalah:** Nilai kembalian yang disimpan di session flash hilang ketika user mengakses halaman cetak PDF karena session flash hanya bertahan satu request.

**Solusi:** Pass kembalian via session flash dan baca di controller sebelum view di-render, kemudian teruskan ke view sebagai variabel biasa. Gunakan session()->flash('kembalian', \) saat menyimpan, dan session('kembalian', 0) saat membaca di controller struk maupun cetakPdf.

---

## 15. Laporan Evaluasi Singkat

### a. Fitur yang Sudah Berjalan dengan Baik

- Login multi-role dengan redirect otomatis sesuai role (admin/petugas/owner)
- CRUD lengkap User, Tarif, Area Parkir, Kendaraan dengan filter dan pencarian
- Transaksi parkir masuk dan keluar dengan perhitungan biaya otomatis
- Fitur pembayaran dengan kembalian real-time dan tombol nominal cepat
- QR code tiket parkir (SVG, tanpa GD extension)
- Scan QR via kamera browser menggunakan html5-qrcode
- Cetak struk PDF thermal (80mm) dengan DomPDF
- Rekap transaksi per bulan dan per hari (per jam) untuk Owner
- Download laporan rekap PDF
- Log aktivitas otomatis untuk semua aksi penting
- Filter dan pencarian di semua halaman data
- Badge durasi parkir dengan warna peringatan (hijau/kuning/merah)
- Shortcut tombol proses keluar dari tabel transaksi
- Navigasi hari sebelum/sesudah di rekap harian Owner

### b. Bug yang Belum Diperbaiki / Keterbatasan

- QR code di PDF menggunakan SVG base64 yang mungkin tidak optimal di semua PDF viewer
- Jika GD extension tidak aktif, QR di PDF kurang tajam dibanding PNG
- Tidak ada fitur notifikasi real-time saat kapasitas parkir hampir penuh
- Belum ada fitur export Excel untuk rekap transaksi
- Belum ada fitur reset password via email
- Scan QR membutuhkan HTTPS di production (Web Camera API requirement)

### c. Rencana Pengembangan Berikutnya

- Aktifkan GD extension untuk QR PNG yang lebih tajam di PDF
- Tambah fitur export Excel (menggunakan maatwebsite/excel)
- Implementasi notifikasi real-time kapasitas parkir (Laravel Broadcasting)
- Tambah fitur reset password via email
- Tambah dashboard chart/grafik pendapatan (Chart.js)
- Implementasi barcode scanner hardware (USB HID)
- Tambah fitur langganan/member parkir dengan tarif khusus
- Deploy ke production dengan HTTPS untuk fitur scan QR kamera

---

*Dokumentasi ini dibuat untuk SmartPark versi 1.0.0 - April 2026*
