<?php

use App\Http\Controllers\Admin\LegalController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:studio'])->group(function () {
    Route::get('/', fn () => Inertia::render('admin/Dashboard', [
        'counts' => ['products' => Product::count(), 'published' => Product::where('is_published', true)->count()],
    ]))->name('dashboard');
    Route::resource('products', ProductController::class)->except('show');
    Route::middleware('can:admin')->group(function () {
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update']);
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::get('legal', [LegalController::class, 'index'])->name('legal');
        Route::put('legal/{page}', [LegalController::class, 'update']);
    });
});
