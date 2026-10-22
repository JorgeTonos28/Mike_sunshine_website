<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StudioSetting;
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
        foreach ([['Mago de capucha violeta', 'mago-capucha-violeta', 'wizard', 'Fantasía'], ['Retrato de un mandril', 'retrato-mandril', 'mandrill', 'Criaturas'], ['Gato esfinge', 'gato-esfinge', 'cat', 'Criaturas'], ['Sabio de túnica verde', 'sabio-tunica-verde', 'monk', 'Personajes'], ['El bufón', 'el-bufon', 'jester', 'Fantasía'], ['Personaje de cabello violeta', 'personaje-cabello-violeta', 'neon', 'Retratos'], ['Personaje de gorro moteado', 'personaje-gorro-moteado', 'arab-alchemist', 'Personajes'], ['El Árabe Alquimista', 'arabe-alquimista-turbante', 'alchemist-turban', 'Originales']] as [$title, $slug, $asset, $category]) {
            $path = '/images/art/'.$asset.'.webp';
            $product = Product::where('kind', 'painting')->whereHas('images', fn ($query) => $query->where('path', $path))->first()
                ?? Product::firstOrCreate(['slug' => $slug], [
                    'title' => $title, 'kind' => 'painting', 'category' => $category,
                    'summary' => 'Una mirada al universo pictórico de Mike Sunshine. Consulta con el estudio los detalles y la disponibilidad de esta obra.',
                    'is_published' => true, 'is_featured' => true, 'availability' => 'inquiry', 'stock' => 1,
                    'content_blocks' => [
                        ['type' => 'heading', 'text' => 'Acerca de esta obra'],
                        ['type' => 'paragraph', 'text' => 'Representación digital regenerada a partir de una pintura de Mike Sunshine. El estudio confirmará los datos de la pieza, su acabado y su disponibilidad antes de cualquier compra.'],
                        ['type' => 'heading', 'text' => 'Antes de llevarla contigo'],
                        ['type' => 'bullets', 'items' => ['Consulta técnica y dimensiones con el estudio.', 'La entrega y su coste se acuerdan de forma personal.', 'Enviar una solicitud no realiza un cobro ni reserva la obra.']],
                    ],
                ]);
            $product->images()->firstOrCreate(['path' => $path], ['alt' => $title.' de Mike Sunshine, representación digital', 'position' => 0]);
        }
        foreach (['wizard' => 'Mago de capucha violeta', 'cat' => 'Gato esfinge', 'monk' => 'Sabio de túnica verde', 'jester' => 'El bufón'] as $asset => $title) {
            $product = Product::firstOrCreate(['slug' => 'escultura-'.$asset], [
                'title' => $title.' en 3D', 'kind' => 'sculpture', 'category' => 'Conceptos 3D',
                'summary' => 'Una interpretación escultórica conceptual basada en una pintura de Mike Sunshine. Consulta con el estudio las posibilidades de realizar esta pieza.',
                'is_published' => true, 'is_featured' => false, 'availability' => 'inquiry', 'stock' => 0,
                'content_blocks' => [
                    ['type' => 'heading', 'text' => 'Del lienzo al volumen'],
                    ['type' => 'paragraph', 'text' => 'Imagen conceptual generada a partir de una pintura de Mike Sunshine para explorar su versión en tres dimensiones. No es una fotografía de una escultura física existente.'],
                    ['type' => 'heading', 'text' => 'Una pieza por conversar'],
                    ['type' => 'bullets', 'items' => ['Mike confirmará si se puede realizar el diseño y cuáles serían sus materiales y acabados.', 'El precio, las dimensiones, los plazos y la entrega se acuerdan con el estudio.', 'Puedes enviar una solicitud aunque todavía no haya existencias. Mike te contactará cuando haya disponibilidad.']],
                ],
            ]);
            $product->images()->firstOrCreate(['path' => '/images/sculptures/'.$asset.'.webp'], ['alt' => $title.', concepto de escultura 3D basado en una pintura de Mike Sunshine', 'position' => 0]);
        }
    }
}
