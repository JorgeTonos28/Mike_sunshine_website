<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogAdminTest extends TestCase
{
    use RefreshDatabase;

    private function productData(): array
    {
        return ['title' => 'Retrato', 'slug' => 'retrato', 'kind' => 'painting', 'stock' => 1, 'availability' => 'inquiry', 'is_published' => false, 'is_featured' => false, 'price' => '1234.56', 'content_blocks' => [['type' => 'heading', 'text' => 'La obra'], ['type' => 'numbered', 'items' => ['Primer detalle']]]];
    }

    public function test_editor_can_create_and_publish_artwork_with_validated_raster_upload(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => 'editor']);
        $data = [...$this->productData(), 'is_published' => true, 'uploads' => [UploadedFile::fake()->image('painting.jpg', 200, 200)]];
        $this->actingAs($editor)->post('/admin/products', $data)->assertSessionHasNoErrors()->assertRedirect();
        $product = Product::first();
        $this->assertSame(123456, $product->price_minor);
        $this->assertTrue($product->is_published);
        $path = basename($product->images->first()->path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_publish_requires_image_and_rejects_html_uploads_and_unsafe_block_types(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->post('/admin/products', [...$this->productData(), 'is_published' => true])->assertSessionHasErrors('uploads');
        $this->post('/admin/products', [...$this->productData(), 'uploads' => [UploadedFile::fake()->createWithContent('evil.svg', '<svg onload="alert(1)"/>')]])->assertSessionHasErrors('uploads.0');
        $this->post('/admin/products', [...$this->productData(), 'content_blocks' => [['type' => 'html', 'text' => '<script>alert(1)</script>']]])->assertSessionHasErrors('content_blocks.0.type');
        $this->assertDatabaseCount('products', 0);
    }

    public function test_editor_cannot_manage_users_settings_or_customer_information(): void
    {
        $this->withoutVite();
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
        $this->post('/admin/users', ['role' => 'admin'])->assertForbidden();
        $this->get('/admin/settings')->assertForbidden();
        $this->get('/admin/about-questionnaire')->assertForbidden();
        $this->get('/admin/legal')->assertForbidden();
        $this->delete('/admin/products/'.Product::factory()->create()->id)->assertForbidden();
    }

    public function test_last_active_administrator_cannot_be_disabled_or_demoted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->put('/admin/users/'.$admin->id, ['name' => $admin->name, 'email' => $admin->email, 'role' => 'editor', 'is_active' => true])->assertSessionHasErrors('role');
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_editor_cannot_remove_an_image_from_another_product(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $one = Product::factory()->create();
        $two = Product::factory()->create();
        $image = $two->images()->create(['path' => '/images/art/cat.png', 'alt' => 'cat']);
        $this->actingAs($editor)->put('/admin/products/'.$one->id, [...$this->productData(), 'remove_images' => [$image->id]])->assertSessionHasErrors('remove_images.0');
        $this->assertDatabaseHas('product_images', ['id' => $image->id]);
    }

    public function test_multipart_upload_can_clear_content_blocks(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => 'editor']);
        $data = $this->productData();
        unset($data['content_blocks']);
        $this->actingAs($editor)->post('/admin/products', [...$data, 'uploads' => [UploadedFile::fake()->image('new.png', 80, 80)]])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame([], Product::first()->content_blocks);
    }

    public function test_gallery_order_and_alt_descriptions_are_editable_for_the_product(): void
    {
        $product = Product::factory()->create();
        $one = $product->images()->create(['path' => '/one.webp', 'alt' => 'One', 'position' => 0]);
        $two = $product->images()->create(['path' => '/two.webp', 'alt' => 'Two', 'position' => 1]);
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->put('/admin/products/'.$product->id, [...$this->productData(), 'image_order' => [$two->id, $one->id], 'image_alts' => [['id' => $two->id, 'alt' => 'Detalle del retrato']]])->assertSessionHasNoErrors();
        $this->assertSame($two->id, $product->fresh()->images->first()->id);
        $this->assertSame('Detalle del retrato', $two->fresh()->alt);
    }
}
