<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\AreaParkirController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Petugas\DashboardPetugasController;
use App\Http\Controllers\Petugas\TransaksiController;
use App\Http\Controllers\Owner\DashboardOwnerController;
use App\Http\Controllers\Owner\RekapController;

// Redirect root
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login', [LoginController::class, 'showForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('tarif', TarifController::class);
    Route::resource('area', AreaParkirController::class);
    Route::resource('kendaraan', KendaraanController::class);

    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log');
});

// Petugas Routes
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [DashboardPetugasController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/masuk', [TransaksiController::class, 'masuk'])->name('transaksi.masuk');
    Route::get('/transaksi/keluar', [TransaksiController::class, 'formKeluar'])->name('transaksi.keluar.form');
    Route::post('/transaksi/keluar', [TransaksiController::class, 'keluar'])->name('transaksi.keluar');
    Route::get('/transaksi/{id}/bayar', [TransaksiController::class, 'formBayar'])->name('transaksi.bayar.form');
    Route::post('/transaksi/{id}/bayar', [TransaksiController::class, 'prosesBayar'])->name('transaksi.bayar');
    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');
    Route::get('/transaksi/{id}/cetak-pdf', [TransaksiController::class, 'cetakPdf'])->name('transaksi.pdf');
});

// Owner Routes
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardOwnerController::class, 'index'])->name('dashboard');
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
    Route::get('/rekap/download-pdf', [RekapController::class, 'downloadPdf'])->name('rekap.pdf');});
