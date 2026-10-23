<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StudioSetting;
use App\Services\InstallArtworkCatalog;
use Illuminate\Database\Seeder;

class DemoArtworkSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            throw new \RuntimeException('Sample artwork must not be seeded into production.');
        }
        $settings = StudioSetting::current();
        $placeholders = ['contact_email' => 'hola@mikesunshine.example', 'phone' => '+1 (809) 555-0100', 'address' => 'Ciudad del estudio, ubicación por confirmar', 'instagram_url' => '/#', 'facebook_url' => '/#', 'youtube_url' => '/#'];
        $settings->update(array_filter($placeholders, fn ($value, $field) => blank($settings->{$field}), ARRAY_FILTER_USE_BOTH));
        StudioSetting::where('logo_path', '/images/brand/logo-dark.png')->update(['logo_path' => '/images/brand/logo-dark-trimmed.png']);
        StudioSetting::whereIn('hero_path', ['/images/art/hero-wizard.png', '/images/art/hero-wizard.webp'])->update(['hero_path' => '/images/compositions/hero-home-wizard.webp']);
        // Correct only the known demo misidentification; retain its ID and business data.
        $legacy = Product::where('kind', 'painting')->where('title', 'El Árabe Alquimista')
            ->whereHas('images', fn ($query) => $query->where('path', '/images/art/arab-alchemist.webp'))->first();
        if ($legacy) {
            $correction = ['title' => 'Personaje de gorro moteado'];
            if ($legacy->slug === 'el-arabe-alquimista' && ! Product::withTrashed()->where('slug', 'personaje-gorro-moteado')->exists()) {
                $correction['slug'] = 'personaje-gorro-moteado';
            }
            $legacy->update($correction);
            $legacy->images()->where('path', '/images/art/arab-alchemist.webp')
                ->where('alt', 'El Árabe Alquimista de Mike Sunshine, representación digital')
                ->update(['alt' => 'Personaje de gorro moteado de Mike Sunshine, representación digital']);
        }
        app(InstallArtworkCatalog::class)->install();
    }
}
