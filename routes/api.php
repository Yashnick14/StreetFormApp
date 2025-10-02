<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;

// ------------------------------
// Auth (public)
// ------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/stripe/create-checkout-session', [StripeController::class, 'createCheckoutSession']);
Route::post('/stripe/webhook', [StripeController::class, 'webhook']); 


// ------------------------------
// Protected (token required)
// ------------------------------
Route::middleware(['auth:sanctum' , 'throttle:10,1'])->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('admins', AdminController::class)->only(['index','store','update','destroy']);
    Route::apiResource('products', ProductController::class);

    Route::apiResource('cart', CartController::class)->except(['show']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::get('/cart/count', [CartController::class, 'getCartCount']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);

    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
