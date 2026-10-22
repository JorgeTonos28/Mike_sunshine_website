<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->isAdmin());
        Gate::define('studio', fn (User $user) => $user->is_active && in_array($user->role, ['admin', 'editor']));
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by(hash('sha256', strtolower(trim((string) $request->input('email'))).'|'.$request->ip())),
            Limit::perMinute(30)->by($request->ip()),
        ]);
        RateLimiter::for('public-form', fn (Request $request) => Limit::perHour(12)->by($request->ip()));
        RateLimiter::for('google', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
