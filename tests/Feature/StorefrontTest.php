<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    public function test_public_pages_and_draft_protection(): void
    {
        $draft = Product::factory()->create(['is_published' => false]);
        $published = Product::factory()->create();
        foreach (['/', '/sobre-mi', '/pinturas', '/esculturas', '/contacto', '/privacidad', '/terminos', '/cookies', '/carrito'] as $path) {
            $this->get($path)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        }
        $this->get('/obras/'.$draft->slug)->assertNotFound();
        $this->get('/obras/'.$published->slug)->assertOk();
        $this->get('/pinturas')->assertInertia(fn (Assert $page) => $page->component('Catalog')->has('products.data', 1)->where('products.data.0.id', $published->id));
    }

    public function test_public_payload_does_not_expose_stock_or_private_customer_fields(): void
    {
        $product = Product::factory()->create();
        $this->get('/obras/'.$product->slug)->assertInertia(fn (Assert $page) => $page->component('Product')->missing('product.stock')->missing('product.customer_email')->where('auth.user', null));
    }

    public function test_unpublished_product_data_is_not_exposed_by_an_old_cart(): void
    {
        $product = Product::factory()->create(['is_published' => false, 'summary' => 'Private draft details']);
        $this->withSession(['cart' => [$product->id => 1]])->get('/carrito')->assertInertia(fn (Assert $page) => $page->component('Cart')->where('cart.items.0.product', null));
    }
}
