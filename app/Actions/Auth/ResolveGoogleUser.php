<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Two\User as GoogleUser;

class ResolveGoogleUser
{
    public function handle(GoogleUser $identity): User
    {
        $verified = ($identity->user['email_verified'] ?? $identity->user['verified_email'] ?? false) === true;
        if (! $verified || ! $identity->getId() || ! filter_var($identity->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages(['email' => 'Google debe devolver una identidad con correo verificado.']);
        }

        return DB::transaction(function () use ($identity) {
            $user = User::query()->where('email', strtolower(trim($identity->getEmail())))->lockForUpdate()->first();
            if (! $user || ! $user->is_active || ! in_array($user->role, ['admin', 'editor']) || ($user->google_id && $user->google_id !== $identity->getId())) {
                throw ValidationException::withMessages(['email' => 'Esta cuenta no tiene acceso al estudio. Contacta con el administrador.']);
            }
            $user->forceFill(['google_id' => $identity->getId(), 'email_verified_at' => $user->email_verified_at ?? now()])->save();

            return $user;
        });
    }
}
