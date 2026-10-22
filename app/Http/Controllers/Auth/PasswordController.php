<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PasswordController extends Controller
{
    public function request()
    {
        return Inertia::render('auth/Password', ['token' => null, 'email' => '']);
    }

    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        Password::sendResetLink(['email' => strtolower(trim($request->input('email')))]);

        return back()->with('status', 'Si el correo tiene acceso, recibirá un enlace para establecer su contraseña.');
    }

    public function edit(Request $request, string $token)
    {
        return Inertia::render('auth/Password', ['token' => $token, 'email' => (string) $request->query('email', '')]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['token' => 'required|string', 'email' => 'required|email|max:255', 'password' => ['required', 'confirmed', 'max:128', \Illuminate\Validation\Rules\Password::min(12)->mixedCase()->numbers()]]);
        $status = Password::reset($data, function ($user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            event(new PasswordReset($user));
        });

        return $status === Password::PasswordReset
            ? to_route('login')->with('status', 'Contraseña actualizada. Puedes entrar al estudio.')
            : back()->withErrors(['email' => 'El enlace no es válido o ha caducado. Solicita uno nuevo.']);
    }
}
