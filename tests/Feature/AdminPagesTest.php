<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_admin_can_open_all_management_pages_and_questionnaire(): void
    {
        $this->withoutVite();
        $this->seed();
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();
        $this->actingAs($admin);
        foreach (['/admin', '/admin/products', '/admin/products/create', '/admin/products/'.$product->id.'/edit', '/admin/settings', '/admin/users', '/admin/orders', '/admin/inquiries', '/admin/legal'] as $path) {
            $this->get($path)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        }
        $this->get('/admin/legal-questionnaire')->assertOk()->assertDownload('Cuestionario-Mike-Sunshine.md');
    }
}
