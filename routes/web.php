<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::redirect('/home', '/');
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/gio-hang', [CartController::class, 'show'])->name('cart.show');
    Route::post('/gio-hang/san-pham/{product}', [CartItemController::class, 'store'])->name('cart.items.store');
    Route::patch('/gio-hang/{item}', [CartItemController::class, 'update'])->whereNumber('item')->name('cart.items.update');
    Route::delete('/gio-hang/{item}', [CartItemController::class, 'destroy'])->whereNumber('item')->name('cart.items.destroy');

    Route::get('/thanh-toan', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/thanh-toan', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/thanh-toan/mua-ngay/{product}', [OrderController::class, 'create'])->name('checkout.buy');
    Route::post('/thanh-toan/mua-ngay/{product}', [OrderController::class, 'store'])->name('checkout.buy.store');
    Route::get('/thanh-toan/hoan-tat/{orderCode}', [OrderController::class, 'show'])->name('checkout.complete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{category:slug}/{product:slug}', [ProductController::class, 'show'])
    ->scopeBindings()
    ->name('products.show');
