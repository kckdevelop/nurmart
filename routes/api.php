<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\BelanjaController;
use App\Http\Controllers\Api\CatatanPesananController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\PengaturanController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Toko Kelontong NURMART (Backend for Flutter Mobile & Web)
|--------------------------------------------------------------------------
*/

// ========================
// 1. PUBLIC ROUTES
// ========================
Route::post('/login', [AuthController::class, 'login']);
Route::get('/penjualan/{id}/cetak-struk', [PenjualanController::class, 'cetakStrukPdf']);
Route::get('/pesanan/{id}/cetak-struk', [PesananController::class, 'cetakStrukPdf']);

// Public Catalog & Order (Akses Umum untuk Pemesanan Barang)
Route::get('/public/produk', [PesananController::class, 'getProdukKatalog']);
Route::post('/public/cek-stok', [PesananController::class, 'cekStok']);
Route::post('/public/pesanan', [PesananController::class, 'storePesanan']);
Route::get('/public/pesanan/{id}/cetak-struk', [PesananController::class, 'cetakStrukPdf']);

// ===================================
// 2. PROTECTED ROUTES (Sanctum Auth)
// ===================================
Route::middleware('auth:sanctum')->group(function () {

    // --- User Session Management (semua role) ---
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Pengaturan Toko (read-only, semua role terautentikasi) ---
    Route::get('/pengaturan', [PengaturanController::class, 'index']);

    // =========================================================================
    // 3. KASIR ROUTES (Role: kasir & pemilik)
    //    Hanya endpoint yang dibutuhkan untuk operasi POS kasir
    // =========================================================================

    // Kategori (Read-only - untuk filter produk di POS)
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);

    // Barang / Produk (Read-only - untuk tampil di halaman POS)
    Route::get('/barang/stok-menipis', [BarangController::class, 'stokMenipis']);
    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);

    // Penjualan / Transaksi POS (Kasir & Pemilik)
    Route::get('/penjualan', [PenjualanController::class, 'index']);
    Route::post('/penjualan', [PenjualanController::class, 'store']);
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show']);

    // Manajemen Pesanan Online Pelanggan (Kasir & Pemilik)
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanan/{id}', [PesananController::class, 'show']);
    Route::put('/pesanan/{id}/status', [PesananController::class, 'updateStatus']);
    Route::delete('/pesanan/{id}', [PesananController::class, 'destroy']);

    // Catatan Pesanan Marketplace Belum Datang (Shopee, Tokopedia, dll - Kasir & Pemilik)
    Route::get('/catatan-pesanan', [CatatanPesananController::class, 'index']);
    Route::get('/catatan-pesanan/{id}', [CatatanPesananController::class, 'show']);
    Route::post('/catatan-pesanan', [CatatanPesananController::class, 'store']);
    Route::put('/catatan-pesanan/{id}', [CatatanPesananController::class, 'update']);
    Route::delete('/catatan-pesanan/{id}', [CatatanPesananController::class, 'destroy']);

    // =========================================================================
    // 4. OWNER ONLY ROUTES (Role: pemilik)
    // =========================================================================
    Route::middleware('role:pemilik')->group(function () {

        // Pengaturan Toko (Update)
        Route::put('/pengaturan', [PengaturanController::class, 'update']);

        // Manajemen User / Kasir (CRUD)
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Master Data: Kategori (Create, Update, Delete)
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

        // Master Data: Supplier (Full CRUD - hanya pemilik)
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);
        Route::post('/supplier', [SupplierController::class, 'store']);
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

        // Master Data: Barang (Create, Update, Delete)
        Route::post('/barang', [BarangController::class, 'store']);
        Route::post('/barang/{id}', [BarangController::class, 'update']); // Support multipart/form-data upload
        Route::put('/barang/{id}', [BarangController::class, 'update']);
        Route::delete('/barang/{id}/gambar', [BarangController::class, 'destroyGambar']); // Hapus gambar saja
        Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

        // Purchasing / Belanja Barang (hanya pemilik)
        Route::get('/belanja', [BelanjaController::class, 'index']);
        Route::post('/belanja', [BelanjaController::class, 'store']);
        Route::get('/belanja/{id}', [BelanjaController::class, 'show']);

        // Laporan & Dasbor (hanya pemilik)
        Route::get('/laporan/dasbor', [LaporanController::class, 'dasbor']);
        Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi']);
    });
});
