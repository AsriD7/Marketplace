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
Route::get('/', function () {
    return view('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [SesiController::class, 'showlogin'])->name('login');
    Route::post('/login', [SesiController::class, 'login'])->name('login.post');
    Route::get('/register', [SesiController::class, 'showregister'])->name('register');
    Route::post('/register', [SesiController::class, 'register'])->name('register.post');
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

    // Keranjang pelanggan
    Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store'); // add to cart
    Route::put('cart/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update'); // update qty
    Route::delete('cart/{item}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy'); // remove item
    Route::post('cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear'); // optional
});

// katalog produk (publik)
Route::get('/catalog', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk', [\App\Http\Controllers\CatalogController::class, 'index']); // alias
Route::get('/produk/{product:slug}', [\App\Http\Controllers\CatalogController::class, 'show'])->name('catalog.show');

// optional: kategori filter via slug
Route::get('/kategori/{category:slug}', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.category');

Route::middleware('auth', 'role:penjual')->group(function () {

    Route::view('/penjual', 'penjual.dashboard')->name('penjual');
});
Route::middleware(['auth', 'role:penjual'])
    ->prefix('penjual')            // optional: ubah url menjadi /penjual/*
    ->name('penjual.')             // ubah name prefix
    ->group(function () {
        // Route::resource('store', \App\Http\Controllers\Seller\StoreController::class)
        //     ->only(['index', 'edit', 'update']);
        Route::get('store', [\App\Http\Controllers\Seller\StoreController::class, 'index'])->name('store.index');
        Route::get('store/edit', [\App\Http\Controllers\Seller\StoreController::class, 'edit'])->name('store.edit');
        Route::post('store/update', [\App\Http\Controllers\Seller\StoreController::class, 'update'])->name('store.update');
        Route::resource('produk', \App\Http\Controllers\Seller\ProductController::class);
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
