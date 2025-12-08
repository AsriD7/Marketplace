<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CatalogController;

// Seller
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;

// Admin
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AdminRatingController;


/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (no login)
|--------------------------------------------------------------------------
*/

// DEV ONLY: Quick switch user tanpa login manual
// ======================================================
// DEV ONLY — QUICK SWITCH LOGIN TANPA PASSWORD
// ======================================================
if (app()->environment('local')) {

    Route::get('/dev/login-as/{id}', function ($id) {

        $user = \App\Models\User::find($id);

        if (! $user) {
            return "User dengan ID $id tidak ditemukan.";
        }

        auth()->login($user);

        return redirect('/')
            ->with('success', "Sekarang login sebagai: {$user->name} (Role: {$user->role})");
    });
}




Route::get('/', fn() => view('dashboard'));
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk', [CatalogController::class, 'index']); // alias
Route::get('/produk/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

// kategori dengan slug
Route::get('/kategori/{category:slug}', [CatalogController::class, 'index'])->name('catalog.category');


/*
|--------------------------------------------------------------------------
| 2. AUTH (LOGIN, REGISTER)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [SesiController::class, 'showlogin'])->name('login');
    Route::post('/login', [SesiController::class, 'login'])->name('login.post');

    Route::get('/register', [SesiController::class, 'showregister'])->name('register');
    Route::post('/register', [SesiController::class, 'register'])->name('register.post');
});

Route::post('/logout', [SesiController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 3. ADMIN MANAGEMENT (ADMIN ROLE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // CRUD Admin Users
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Payment Validation
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('payments/pending', [AdminPaymentController::class, 'pending'])->name('payments.pending');
        Route::post('payments/{payment}/validate', [AdminPaymentController::class, 'validatePayment'])->name('payments.validate');
        Route::post('payments/{payment}/reject', [AdminPaymentController::class, 'rejectPayment'])->name('payments.reject');

        // Rating Moderation
        Route::get('ratings', [AdminRatingController::class, 'index'])->name('ratings.index');
        Route::delete('ratings/{rating}', [AdminRatingController::class, 'destroy'])->name('ratings.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| 4. CUSTOMER (PELANGGAN) ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pelanggan'])->group(function () {

    Route::view('/pelanggan', 'pelanggan.dashboard')->name('pelanggan');

    /*
    | CART
    */
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    /*
    | CHECKOUT
    */
    Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    /*
    | ORDER HISTORY CUSTOMER
    */
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Upload bukti pembayaran
    Route::post('orders/{order}/payment', [PaymentController::class, 'upload'])->name('orders.payment.upload');

    // Rating via History
    Route::post('orders/{order}/rate', [\App\Http\Controllers\RatingController::class, 'store'])
        ->name('orders.rate');
});


/*
|--------------------------------------------------------------------------
| 5. SELLER (PENJUAL) ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:penjual'])
    ->prefix('penjual')
    ->name('penjual.')
    ->group(function () {

        Route::get('/', [SellerDashboardController::class, 'index'])->name('dashboard');

        /*
        | STORE MANAGEMENT
        */
        Route::get('store', [StoreController::class, 'index'])->name('store.index');
        Route::get('store/edit', [StoreController::class, 'edit'])->name('store.edit');
        Route::post('store/update', [StoreController::class, 'update'])->name('store.update');

        /*
        | PRODUCT CRUD
        */
        Route::resource('produk', ProductController::class);

        /*
        | SELLER ORDERS
        */
        Route::get('orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
