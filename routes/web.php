<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\CustomerManagement;
use App\Livewire\Admin\OrderManagement;
use App\Livewire\Customer\Cart;
use App\Livewire\Customer\Checkout;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\OrderController;
use App\Livewire\Customer\OrderConfirmation;
use App\Livewire\Customer\MenProducts;
use App\Livewire\Customer\WomenProducts;
use App\Livewire\Customer\AllProducts;
use App\Livewire\Customer\Orders;


// Public routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/wishlist', 'customers.wishlist')->name('wishlist.index');

// Root route
Route::get('/', fn () => redirect()->route('login'));

// Customer routes
Route::middleware(['auth','customer'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/men', MenProducts::class)->name('men.products');
    Route::get('/women', WomenProducts::class)->name('women.products');
    Route::get('/all', AllProducts::class)->name('all.products');
    Route::get('/products/{id}/view', [ProductController::class, 'view'])->name('products.view');
    Route::get('/cart', Cart::class)->name('cart.index');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/order/confirmation/{orderId}', OrderConfirmation::class)->name('order.confirmation');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('customer.orders');
    Route::get('/orders/{orderId}/tracking', [OrderController::class, 'show'])->name('orders.tracking');

});

// Stripe checkout routes
Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');


// Admin routes
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/products', ProductManagement::class)->name('admin.products');
    Route::get('/admin/customers', CustomerManagement::class)->name('admin.customers');
    Route::get('/admin/orders', OrderManagement::class)->name('admin.orders');
});
