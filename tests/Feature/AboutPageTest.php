<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StudioSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_artist_page_uses_the_configured_portrait_and_does_not_expose_private_artwork(): void
    {
        $this->withoutVite();
        $portrait = '/media/12345678-1234-1234-1234-123456789abc.webp';
        StudioSetting::current()->update(['portrait_path' => $portrait]);
        $published = Product::factory()->create(['is_published' => true]);
        $published->images()->create(['path' => '/images/art/cat.webp', 'alt' => 'Gato de Mike']);
        $draft = Product::factory()->create(['is_published' => false]);
        $draft->images()->create(['path' => '/private-draft.webp', 'alt' => 'Borrador privado']);
        $archived = Product::factory()->create(['is_published' => true]);
        $archived->images()->create(['path' => '/archived.webp', 'alt' => 'Archivada']);
        $archived->delete();

        $this->get('/sobre-mi')->assertOk()->assertInertia(fn (Assert $page) => $page->component('About')
            ->where('studio.portrait_path', $portrait)->where('auth.user', null)
            ->has('works', 1)->where('works.0.id', $published->id)
            ->where('works.0.image.path', '/images/art/cat.webp')
            ->missing('works.0.stock')->missing('works.0.content_blocks'));
    }

    public function test_artist_page_can_open_before_any_artwork_is_published(): void
    {
        $this->withoutVite();
        $this->get('/sobre-mi')->assertOk()->assertInertia(fn (Assert $page) => $page->component('About')->has('works', 0)->where('studio.portrait_path', '/images/artist/mike-portrait.webp'));
    }
}
