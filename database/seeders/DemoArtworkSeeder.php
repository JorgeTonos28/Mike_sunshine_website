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
        StudioSetting::where('logo_path', '/images/brand/logo-dark.png')->update(['logo_path' => '/images/brand/logo-dark-trimmed.png']);
        StudioSetting::where('hero_path', '/images/art/hero-wizard.png')->update(['hero_path' => '/images/art/hero-wizard.webp']);
        foreach ([['Mago de capucha violeta', 'mago-capucha-violeta', 'wizard', 'Fantasía'], ['Retrato de un mandril', 'retrato-mandril', 'mandrill', 'Criaturas'], ['Gato esfinge', 'gato-esfinge', 'cat', 'Criaturas'], ['Sabio de túnica verde', 'sabio-tunica-verde', 'monk', 'Personajes'], ['El bufón', 'el-bufon', 'jester', 'Fantasía'], ['Personaje de cabello violeta', 'personaje-cabello-violeta', 'neon', 'Retratos']] as [$title, $slug, $asset, $category]) {
            $product = Product::firstOrCreate(['slug' => $slug], [
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
            $product->images()->firstOrCreate(['path' => '/images/art/'.$asset.'.webp'], ['alt' => $title.' de Mike Sunshine, representación digital', 'position' => 0]);
        }
    }
}
