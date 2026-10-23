<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioSetting extends Model
{
    protected $attributes = ['currency' => 'DOP'];

    protected $fillable = ['name', 'legal_name', 'tagline', 'bio', 'contact_email', 'phone', 'address', 'instagram_url', 'facebook_url', 'youtube_url', 'logo_path', 'favicon_path', 'currency', 'hero_title', 'hero_description', 'hero_path', 'portrait_path'];

    public static function current(): self
    {
        return self::query()->firstOrCreate(['id' => 1], self::defaults());
    }

    public static function defaults(): array
    {
        return [
            'name' => 'Mike Sunshine', 'tagline' => 'Pintor · Escultor 3D · Productor musical',
            'bio' => 'La música, la pintura y la escultura son distintas formas de contar una historia. Mike Sunshine explora personajes, retratos y mundos imaginarios a través de su obra.',
            'hero_title' => 'Mike Sunshine',
            'hero_description' => 'Personajes con alma. Pinturas y esculturas que exploran la imaginación, el color y el carácter.',
            'logo_path' => '/images/brand/logo-dark-trimmed.png', 'favicon_path' => '/images/brand/favicon.png',
            'hero_path' => '/images/compositions/hero-home-wizard.webp',
            'portrait_path' => '/images/artist/mike-portrait.webp',
        ];
    }

    public function publicData(): array
    {
        return $this->only($this->getFillable());
    }
}
