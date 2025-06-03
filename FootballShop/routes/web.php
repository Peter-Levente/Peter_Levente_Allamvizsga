<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// ========== Kezdőlap ==========

// Főoldal (terméklista megjelenítése)
Route::get('/', [ProductController::class, 'index'])->name('home');

// ========== Termékek ==========

// Termékek listázása
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Egy konkrét termék részletei
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.details');

// Termékek megjelenítése kategória szerint
Route::get('/category/{category}', [ProductController::class, 'showcategory'])->name('products.category');

// ========== Felhasználói hitelesítés ==========

// Bejelentkezési űrlap
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Bejelentkezési kérelem feldolgozása
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Regisztrációs űrlap
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Regisztrációs adatbeküldés
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// ========== Védett (autentikált) útvonalak ==========
Route::middleware(['auth'])->group(function () {

    // Felhasználói dashboard (bejelentkezés után)
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // ========== Kosár műveletek ==========

    // Kosár megtekintése
    Route::get('/cart', [CartController::class, 'index'])->name('cart.mycart');

    // Termék hozzáadása a kosárhoz
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

    // Kosárelem mennyiségének frissítése
    Route::put('/cart/{cart}', [CartController::class, 'updateCart'])->name('cart.update');

    // Kosárelem eltávolítása
    Route::delete('/cart/{cart}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // Lejárt kosárelemek törlése
    Route::get('/cart/clear-expired', [CartController::class, 'clearExpiredCarts']);

    // ========== Rendelések ==========

    // Pénztár oldal
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');

    // Rendelés leadása
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.place');

    // Köszönő oldal rendelés után
    Route::get('/thank-you/{order}', [OrderController::class, 'thankYou'])->name('orders.thank_you');

    // Saját rendelések megtekintése
    Route::get('/myOrders', [OrderController::class, 'myOrders'])->name('orders.myorders');

    // Rendelés törlése
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

// ========== Laravel alapértelmezett Auth útvonalak ==========
Auth::routes();
