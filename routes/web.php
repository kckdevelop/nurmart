<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('app');
})->name('login');

// Halaman Publik Pemesanan Barang (Akses Umum)
Route::get('/pesan', function () {
    return view('order');
})->name('pesan');

Route::get('/order', function () {
    return view('order');
});

Route::get('/katalog', function () {
    return view('order');
});
