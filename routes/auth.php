<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:login');
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->middleware('throttle:google')->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->middleware('throttle:google');
    Route::get('/forgot-password', [PasswordController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'email'])->middleware('throttle:public-form')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'update'])->middleware('throttle:public-form')->name('password.update');
});
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');
