<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');


Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
