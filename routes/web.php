<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Notifikasi routes
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/unread', [NotifikasiController::class, 'getUnread'])->name('notifikasi.unread');
    Route::post('/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.baca-semua');
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
    
    // Admin only for barang management
    Route::middleware('role:admin')->group(function () {
        Route::resource('barang', BarangController::class);
    });
    
    // Operator only routes (peminjaman & pengembalian)
    Route::middleware('role:operator')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::get('/api/barang/{barang}', [PeminjamanController::class, 'getBarangInfo'])->name('api.barang.info');
        
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/{peminjaman}', [PengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])->name('pengembalian.store');
        
        Route::post('/peminjaman/{peminjaman}/serahkan', [PersetujuanController::class, 'serahkan'])->name('peminjaman.serahkan');
    });
    
    // Kabag Umum routes
    Route::middleware('role:kabag_umum')->group(function () {
        Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
        Route::get('/persetujuan/{peminjaman}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
        Route::post('/persetujuan/{peminjaman}/approve', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
        Route::post('/persetujuan/{peminjaman}/reject', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');
    });
    
    // Laporan routes
    Route::middleware('role:admin,kabag_umum')->group(function () {
        Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/peminjaman/pdf', [LaporanController::class, 'peminjamanPdf'])->name('laporan.peminjaman.pdf');
        Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
        Route::get('/laporan/pengembalian/pdf', [LaporanController::class, 'pengembalianPdf'])->name('laporan.pengembalian.pdf');
        Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
        Route::get('/laporan/stok/pdf', [LaporanController::class, 'stokPdf'])->name('laporan.stok.pdf');
    });
});
