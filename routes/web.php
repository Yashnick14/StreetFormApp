<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\CustomerManagement;
use App\Livewire\Customer\Cart;
use App\Livewire\Customer\Checkout;
use App\Http\Controllers\StripeController;

// Public routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Root → always send to login
Route::get('/', fn () => redirect()->route('login'));

// Customer routes
Route::middleware(['auth','customer'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/men', [HomeController::class, 'men'])->name('men.products');
    Route::get('/women', [HomeController::class, 'women'])->name('women.products');
    Route::get('/products/{id}/view', [ProductController::class, 'view'])->name('products.view');
    Route::get('/cart', Cart::class)->name('cart.index');
    Route::get('/checkout', Checkout::class)->name('checkout');
});

// Stripe checkout routes
Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');


// Admin routes
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/products', ProductManagement::class)->name('admin.products');
    Route::get('/admin/customers', CustomerManagement::class)->name('admin.customers');
});
