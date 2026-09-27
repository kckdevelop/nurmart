<?php

use Illuminate\Support\Facades\Route;

// Halaman Publik Pemesanan Barang (Akses Utama / Root)
Route::get('/', function () {
    return view('order');
})->name('home');

Route::get('/pesan', function () {
    return view('order');
})->name('pesan');

Route::get('/order', function () {
    return view('order');
});

Route::get('/katalog', function () {
    return view('order');
});

// Halaman Login & Manajemen Admin/Kasir POS
Route::get('/admin', function () {
    return view('app');
})->name('admin');

Route::get('/login', function () {
    return view('app');
})->name('login');
