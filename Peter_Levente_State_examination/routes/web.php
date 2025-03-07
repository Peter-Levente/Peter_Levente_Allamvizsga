<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Kezdőlap
Route::get('/', [ProductController::class, 'index'])->name('home');

// Termékek
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'showcategory'])->name('products.category');

// Regisztráció és bejelentkezés
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


// Védett oldalak
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Kosár
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{cart}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // Rendelések
    Route::get('/myOrders', [OrderController::class, 'myOrders'])->name('orders.myorders');
});


// Laravel beépített Auth útvonalak
Auth::routes();
