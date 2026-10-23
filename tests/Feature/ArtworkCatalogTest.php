<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\InstallArtworkCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ArtworkCatalogTest extends TestCase
{
    use RefreshDatabase;

    private array $catalog;

    private string $fixtureDirectory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catalog = json_decode(file_get_contents(database_path('catalog/artworks.json')), true, flags: JSON_THROW_ON_ERROR);
        $this->fixtureDirectory = storage_path('framework/testing/catalog-'.Str::uuid());
        File::ensureDirectoryExists($this->fixtureDirectory.'/catalog');
        $this->app->useDatabasePath($this->fixtureDirectory);
        $this->writeCatalog();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->fixtureDirectory);
        parent::tearDown();
    }

    private function writeCatalog(): void
    {
        File::put(database_path('catalog/artworks.json'), json_encode($this->catalog, JSON_THROW_ON_ERROR));
    }

    public function test_reviewed_catalog_installs_in_production_without_private_or_demo_business_data(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $result = app(InstallArtworkCatalog::class)->install();

        $this->assertSame(['created' => 12, 'skipped' => 0, 'paintings' => 8, 'sculptures' => 4], $result);
        $this->assertDatabaseCount('products', 12);
        $this->assertDatabaseCount('product_images', 12);
        $this->assertSame(12, Product::where('is_published', true)->whereNull('price_minor')->whereNull('dimensions')->count());
        $this->assertDatabaseHas('products', ['slug' => 'arabe-alquimista-turbante', 'title' => 'El Árabe Alquimista']);
        foreach (Product::where('kind', 'sculpture')->get() as $sculpture) {
            $this->assertStringContainsString('No es una fotografía de una escultura física existente', json_encode($sculpture->content_blocks, JSON_UNESCAPED_UNICODE));
        }
        foreach (['studio_settings', 'users', 'orders', 'inquiries', 'jobs'] as $table) {
            $this->assertDatabaseCount($table, 0);
        }
    }

    public function test_reinstall_preserves_edits_drafts_archived_pieces_and_renamed_products(): void
    {
        $installer = app(InstallArtworkCatalog::class);
        $installer->install();
        $renamed = Product::where('slug', 'mago-capucha-violeta')->firstOrFail();
        $renamed->update(['title' => 'Nombre confirmado', 'slug' => 'nombre-confirmado', 'price_minor' => 12345, 'stock' => 7, 'is_published' => false, 'content_blocks' => [['type' => 'paragraph', 'text' => 'Detalles propios']]]);
        $renamed->images()->update(['alt' => 'Mi descripción', 'position' => 2]);
        $archived = Product::where('slug', 'gato-esfinge')->firstOrFail();
        $archived->delete();
        $snapshot = $renamed->fresh()->getAttributes();

        $this->assertSame(0, $installer->install()['created']);
        $this->assertSame(12, $installer->install()['skipped']);
        $this->assertSame($snapshot, $renamed->fresh()->getAttributes());
        $this->assertTrue(Product::withTrashed()->findOrFail($archived->id)->trashed());
        $this->assertSame('Mi descripción', $renamed->images->sole()->alt);
        $this->assertSame(2, $renamed->images->sole()->position);
        $this->assertSame(12, Product::withTrashed()->count());
        $this->assertDatabaseCount('product_images', 12);
    }

    public function test_dry_run_predicts_new_and_existing_products_without_writing(): void
    {
        Product::factory()->create(['slug' => $this->catalog['products'][0]['slug']]);
        $this->assertSame(['created' => 11, 'skipped' => 1, 'paintings' => 7, 'sculptures' => 4], app(InstallArtworkCatalog::class)->install(dryRun: true));
        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_missing_images_abort_the_entire_import_before_any_database_write(): void
    {
        $this->catalog['products'][11]['images'][0]['path'] = '/images/sculptures/missing-catalog-file.webp';
        $this->writeCatalog();
        try {
            app(InstallArtworkCatalog::class)->install();
            $this->fail('Missing image accepted');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('Falta una imagen válida', $exception->getMessage());
        }
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_unsafe_image_paths_and_duplicate_slugs_are_rejected_without_writing(): void
    {
        foreach (['/../.env', 'https://example.com/picture.webp', '/media/not-versioned.webp'] as $path) {
            $this->catalog['products'][0]['images'][0]['path'] = $path;
            $this->writeCatalog();
            try {
                app(InstallArtworkCatalog::class)->install();
                $this->fail('Unsafe path accepted');
            } catch (ValidationException) {
                $this->assertDatabaseCount('products', 0);
            }
        }
        $this->catalog['products'][0]['images'][0]['path'] = '/images/art/wizard.webp';
        $this->catalog['products'][1]['slug'] = $this->catalog['products'][0]['slug'];
        $this->writeCatalog();
        $this->expectException(ValidationException::class);
        app(InstallArtworkCatalog::class)->install();
    }

    public function test_database_failure_rolls_back_products_and_images_together(): void
    {
        ProductImage::creating(function ($image) {
            if ($image->path === '/images/art/mandrill.webp') {
                throw new \RuntimeException('Simulated database write failure');
            }
        });
        try {
            app(InstallArtworkCatalog::class)->install();
            $this->fail('Write failure not raised');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated database write failure', $exception->getMessage());
        } finally {
            ProductImage::flushEventListeners();
        }
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('product_images', 0);
    }
}
