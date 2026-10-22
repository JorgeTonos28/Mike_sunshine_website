<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

Route::get('/media/{filename}', function (string $filename) {
    abort_unless(preg_match('/^[a-f0-9-]{36}\.webp$/', $filename), 404);
    abort_unless(Storage::disk('public')->exists($filename), 404);

    return response()->file(Storage::disk('public')->path($filename), ['Content-Type' => 'image/webp', 'Cache-Control' => 'public, max-age=31536000, immutable']);
});

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/pinturas', [StorefrontController::class, 'catalog'])->defaults('kind', 'painting')->name('paintings');
Route::get('/esculturas', [StorefrontController::class, 'catalog'])->defaults('kind', 'sculpture')->name('sculptures');
Route::get('/obras/{product:slug}', [StorefrontController::class, 'show'])->name('product');
Route::get('/contacto', [StorefrontController::class, 'contact'])->name('contact');
Route::post('/contacto', [InquiryController::class, 'store'])->middleware('throttle:public-form');
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito/{product}', [CartController::class, 'update'])->middleware('throttle:60,1');
Route::delete('/carrito/{id}', [CartController::class, 'remove'])->whereNumber('id');
Route::get('/solicitar', [OrderController::class, 'create'])->name('order.create');
Route::post('/solicitar', [OrderController::class, 'store'])->middleware('throttle:public-form');
Route::get('/pedido/recibido', [OrderController::class, 'receipt'])->name('order.receipt');
Route::get('/{slug}', [StorefrontController::class, 'legal'])->whereIn('slug', ['privacidad', 'terminos', 'cookies']);
