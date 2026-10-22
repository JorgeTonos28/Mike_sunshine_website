<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateStudioAdmin extends Command
{
    protected $signature = 'studio:admin {email?}';

    protected $description = 'Create the first studio administrator securely';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email') ?: $this->ask('Correo de Mike')));
        if (User::where('email', $email)->exists()) {
            $this->error('El correo ya existe. Administra esa cuenta desde el panel.');

            return self::FAILURE;
        }
        $name = $this->ask('Nombre', 'Mike Sunshine');
        $password = $this->secret('Contraseña (12 caracteres mínimo, mayúsculas, minúsculas y números)');
        $validator = Validator::make(compact('name', 'email', 'password'), ['name' => 'required|string|max:255', 'email' => 'required|email|max:255', 'password' => ['required', 'max:128', Password::min(12)->mixedCase()->numbers()]]);
        if ($validator->fails()) {
            $this->error(implode(' ', $validator->errors()->all()));

            return self::FAILURE;
        }
        $user = User::create(compact('name', 'email', 'password'));
        $user->forceFill(['role' => 'admin', 'is_active' => true])->save();
        $this->info('Administrador creado. Accede desde /login.');

        return self::SUCCESS;
    }
}
