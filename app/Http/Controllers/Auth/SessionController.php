<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SessionController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/Login', ['googleEnabled' => filled(config('services.google.client_id')) && filled(config('services.google.client_secret'))]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255', 'password' => 'required|string|max:128', 'remember' => 'sometimes|boolean']);
        if (! Auth::attempt(['email' => strtolower(trim($data['email'])), 'password' => $data['password'], 'is_active' => true], (bool) ($data['remember'] ?? false))) {
            throw ValidationException::withMessages(['email' => 'El correo o la contraseña no son correctos.']);
        }
        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
