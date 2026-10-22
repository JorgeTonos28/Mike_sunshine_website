<?php

namespace App\Services;

use App\Models\StudioSetting;
use Illuminate\Support\Facades\Validator;

class StudioMailbox
{
    public function recipient(): string
    {
        $override = trim((string) config('studio.email'));
        $recipient = $override !== '' ? $override : trim((string) StudioSetting::current()->contact_email);

        if (Validator::make(['email' => $recipient], ['email' => 'required|email:rfc|max:255'])->fails()) {
            throw new \RuntimeException('Configura STUDIO_EMAIL o un correo de contacto válido en el panel.');
        }

        return $recipient;
    }
}
