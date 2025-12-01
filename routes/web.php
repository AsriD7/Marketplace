<?php

use App\Models\Profile;
use App\Http\Controllers\deep;
use App\Http\Controllers\kontak;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/halo', function () {
//     return "asri";
// });

// Route::get('/halo/{nama}', function ($nama) {
//     return "Halo $nama";
//});
Route::middleware('guest')->group(function () {
    Route::get('/login', [SesiController::class, 'showlogin'])->name('login');
    Route::post('/login', [SesiController::class, 'login'])->name('login.post');
    Route::get('/register', [SesiController::class, 'showregister'])->name('register');
    Route::post('/register', [SesiController::class, 'register'])->name('register.post');
Route::get('/cek', function () {
    return view('pelanggan.cek');
});
    
});
Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

Route::middleware('auth', 'role:pelanggan')->group(function () {
    Route::view('/pelanggan', 'pelanggan.dashboard')->name('pelanggan');
});

Route::middleware('auth', 'role:penjual')->group(function () {
    Route::view('/penjual', 'penjual.dashboard')->name('penjual');
});

Route::get('/about', function () {
    return "Tentang kami";
});

Route::get('/produk/{produk}', function ($produk) {
    return "Produk kami adalah $produk";
});


Route::get('/Tentang', function () {
    return view('tentang');
});
