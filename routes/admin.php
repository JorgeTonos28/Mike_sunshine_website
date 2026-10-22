<?php

use App\Http\Controllers\Admin\LegalController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\StudioSetting;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:studio'])->group(function () {
    Route::get('/', fn () => Inertia::render('admin/Dashboard', [
        'counts' => ['products' => Product::count(), 'published' => Product::where('is_published', true)->count(), 'orders' => request()->user()->isAdmin() ? Order::whereIn('status', ['received', 'awaiting_stock'])->count() : null, 'inquiries' => request()->user()->isAdmin() ? Inquiry::whereNull('read_at')->count() : null],
        'setup' => request()->user()->isAdmin() ? ['email' => filled(StudioSetting::current()->contact_email) || filled(config('studio.email')), 'google' => filled(config('services.google.client_id'))] : null,
    ]))->name('dashboard');
    Route::resource('products', ProductController::class)->except('show');
    Route::middleware('can:admin')->group(function () {
        Route::get('legal-questionnaire', fn () => response()->download(base_path('docs/cuestionario-mike.md'), 'Cuestionario-Mike-Sunshine.md', ['Content-Type' => 'text/markdown; charset=UTF-8']));
        Route::get('about-questionnaire', fn () => response()->download(base_path('docs/cuestionario-sobre-mike.md'), 'Cuestionario-Sobre-Mike-Sunshine.md', ['Content-Type' => 'text/markdown; charset=UTF-8']))->name('about-questionnaire');
        Route::get('orders', [OrderController::class, 'index'])->name('orders');
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::put('orders/{order}', [OrderController::class, 'update']);
        Route::post('orders/{order}/retry', [OrderController::class, 'retry'])->middleware('throttle:10,1');
        Route::get('inquiries', [OrderController::class, 'inquiries']);
        Route::put('inquiries/{inquiry}/read', [OrderController::class, 'read']);
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update']);
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::get('legal', [LegalController::class, 'index'])->name('legal');
        Route::put('legal/{page}', [LegalController::class, 'update']);
    });
});
