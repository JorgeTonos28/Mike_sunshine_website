<?php

namespace Tests\Feature;

use App\Services\InstallArtworkCatalog;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InstallCatalogCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_install_requires_force_but_dry_run_is_available(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->artisan('studio:catalog-install')->expectsOutputToContain('--force')->assertFailed();
        $this->artisan('studio:catalog-install', ['--dry-run' => true])->expectsOutputToContain('no se han guardado cambios')->assertSuccessful();
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_production_command_loads_both_catalogs_and_can_run_again_without_duplicates(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->withoutVite();
        $this->artisan('studio:catalog-install', ['--force' => true])->assertSuccessful();
        $this->artisan('studio:catalog-install', ['--force' => true])->assertSuccessful();
        $this->assertDatabaseCount('products', 12);
        $this->assertDatabaseCount('product_images', 12);
        $this->get('/pinturas')->assertOk()->assertInertia(fn ($page) => $page->has('products.data', 8));
        $this->get('/esculturas')->assertOk()->assertInertia(fn ($page) => $page->has('products.data', 4));
        $this->get('/obras/arabe-alquimista-turbante')->assertOk();
    }

    public function test_invalid_catalog_and_missing_assets_return_actionable_errors(): void
    {
        $this->mock(InstallArtworkCatalog::class)->shouldReceive('install')->once()->andThrow(ValidationException::withMessages(['products.0.slug' => 'Identificador inválido.']));
        $this->artisan('studio:catalog-install')->expectsOutputToContain('Identificador inválido')->assertFailed();
        $this->mock(InstallArtworkCatalog::class)->shouldReceive('install')->once()->andThrow(new \RuntimeException('Falta una imagen válida del catálogo.'));
        $this->artisan('studio:catalog-install')->expectsOutputToContain('Falta una imagen válida')->assertFailed();
        $this->assertDatabaseCount('products', 0);
    }

    public function test_database_connection_errors_do_not_expose_connection_details(): void
    {
        $this->mock(InstallArtworkCatalog::class)->shouldReceive('install')->once()->andThrow(new QueryException('private-connection', 'private-sql', [], new \PDOException('private-password')));
        $this->artisan('studio:catalog-install')->expectsOutputToContain('La operación se ha revertido')->doesntExpectOutputToContain('private-password')->assertFailed();
    }
}
