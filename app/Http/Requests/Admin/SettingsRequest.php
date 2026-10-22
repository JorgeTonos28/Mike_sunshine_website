<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $socialUrl = ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
            if ($value !== '/#' && (! filter_var($value, FILTER_VALIDATE_URL) || parse_url($value, PHP_URL_SCHEME) !== 'https')) {
                $fail('Usa una URL HTTPS o /# para un enlace de muestra.');
            }
        }];

        return [
            'name' => 'required|string|max:180', 'tagline' => 'nullable|string|max:255', 'bio' => 'nullable|string|max:8000',
            'contact_email' => 'nullable|email|max:255', 'phone' => 'nullable|string|max:60', 'address' => 'nullable|string|max:1000',
            'instagram_url' => $socialUrl, 'facebook_url' => $socialUrl, 'youtube_url' => $socialUrl,
            'currency' => 'required|in:DOP,USD,EUR', 'hero_title' => 'required|string|max:80', 'hero_description' => 'nullable|string|max:400',
            'logo' => 'nullable|file|mimetypes:image/png,image/webp,image/jpeg|max:4096|dimensions:max_width=4000,max_height=4000',
            'favicon' => 'nullable|file|mimetypes:image/png,image/webp|max:1024|dimensions:max_width=1024,max_height=1024',
            'hero' => 'nullable|file|mimetypes:image/png,image/webp,image/jpeg|max:8192|dimensions:max_width=6000,max_height=6000',
        ];
    }
}
