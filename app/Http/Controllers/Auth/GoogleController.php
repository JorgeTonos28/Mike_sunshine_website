<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ResolveGoogleUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        abort_unless(config('services.google.client_id') && config('services.google.client_secret'), 503, 'Acceso con Google pendiente de configuración.');

        return Socialite::driver('google')->scopes(['openid', 'email', 'profile'])->redirect();
    }

    public function callback(Request $request, ResolveGoogleUser $resolver)
    {
        try {
            $identity = Socialite::driver('google')->user();
            $user = $resolver->handle($identity);
        } catch (ValidationException $exception) {
            return to_route('login')->withErrors($exception->errors());
        } catch (\Throwable) {
            // OAuth errors may contain tokens. Never log their raw payload/message.
            return to_route('login')->withErrors(['email' => 'No se pudo completar el acceso con Google. Inténtalo de nuevo.']);
        }
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }
}
