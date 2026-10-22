<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StudioSetting;
use Database\Seeders\DemoArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoArtworkSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_separates_the_alchemist_and_remains_idempotent(): void
    {
        $this->seed(DemoArtworkSeeder::class);
        $this->seed(DemoArtworkSeeder::class);

        $this->assertSame(8, Product::where('kind', 'painting')->count());
        $this->assertSame(4, Product::where('kind', 'sculpture')->count());
        $alchemist = Product::where('slug', 'arabe-alquimista-turbante')->firstOrFail();
        $this->assertSame('El Árabe Alquimista', $alchemist->title);
        $this->assertSame('/images/art/alchemist-turban.webp', $alchemist->images->sole()->path);
        $this->assertTrue($alchemist->isRequestable());
        $this->assertNull($alchemist->price_minor);
        $this->assertDatabaseHas('products', ['slug' => 'personaje-gorro-moteado', 'title' => 'Personaje de gorro moteado']);
    }

    public function test_legacy_identity_is_corrected_without_replacing_the_product_or_its_data(): void
    {
        $legacy = Product::factory()->create([
            'title' => 'El Árabe Alquimista', 'slug' => 'el-arabe-alquimista',
            'kind' => 'painting', 'price_minor' => 45000, 'stock' => 3, 'summary' => 'Descripción revisada por Mike.',
        ]);
        $legacy->images()->create(['path' => '/images/art/arab-alchemist.webp', 'alt' => 'El Árabe Alquimista de Mike Sunshine, representación digital', 'position' => 0]);
        StudioSetting::current()->update(['contact_email' => 'mike@example.com', 'instagram_url' => 'https://www.instagram.com/mike']);

        $this->seed(DemoArtworkSeeder::class);
        $this->seed(DemoArtworkSeeder::class);

        $legacy->refresh();
        $this->assertSame('Personaje de gorro moteado', $legacy->title);
        $this->assertSame('personaje-gorro-moteado', $legacy->slug);
        $this->assertSame(45000, $legacy->price_minor);
        $this->assertSame(3, $legacy->stock);
        $this->assertSame('Descripción revisada por Mike.', $legacy->summary);
        $this->assertSame(12, Product::count());
        $this->assertSame('Personaje de gorro moteado de Mike Sunshine, representación digital', $legacy->images->sole()->alt);
        $this->assertSame('mike@example.com', StudioSetting::current()->contact_email);
        $this->assertSame('https://www.instagram.com/mike', StudioSetting::current()->instagram_url);
    }

    public function test_custom_title_and_slug_are_preserved_without_duplicating_the_same_demo_image(): void
    {
        $custom = Product::factory()->create(['title' => 'Título confirmado por Mike', 'slug' => 'titulo-confirmado', 'kind' => 'painting']);
        $custom->images()->create(['path' => '/images/art/arab-alchemist.webp', 'alt' => 'Descripción propia', 'position' => 0]);

        $this->seed(DemoArtworkSeeder::class);

        $this->assertSame('Título confirmado por Mike', $custom->fresh()->title);
        $this->assertSame('titulo-confirmado', $custom->fresh()->slug);
        $this->assertSame('Descripción propia', $custom->images->sole()->alt);
        $this->assertSame(12, Product::count());
    }
}
