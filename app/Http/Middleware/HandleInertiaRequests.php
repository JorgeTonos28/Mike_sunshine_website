<?php

namespace App\Http\Middleware;

use App\Models\StudioSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'studio' => fn () => StudioSetting::current()->publicData(),
            'auth' => ['user' => $request->user()?->only(['id', 'name', 'email', 'role'])],
            'cartCount' => fn () => array_sum($request->session()->get('cart', [])),
            'flash' => ['success' => fn () => $request->session()->get('success'), 'status' => fn () => $request->session()->get('status')],
        ];
    }
}
