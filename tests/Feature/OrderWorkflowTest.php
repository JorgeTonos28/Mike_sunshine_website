<?php

namespace Tests\Feature;

use App\Actions\Orders\CreateOrder;
use App\Jobs\NotifyStudio;
use App\Mail\StudioMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\StudioSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['customer_name' => 'Cliente de prueba', 'customer_email' => 'buyer@example.test', 'customer_phone' => '8090000000', 'delivery_method' => 'shipping', 'shipping_address' => 'Dirección privada de prueba', 'message' => 'Consulta de entrega', 'privacy' => true, 'terms' => true];
    }

    public function test_request_snapshots_authoritative_prices_and_durable_encrypted_notification(): void
    {
        $product = Product::factory()->create(['price_minor' => 123456]);
        $token = (string) Str::uuid();
        $this->withSession(['cart' => [$product->id => 1], 'checkout_token' => $token])
            ->post('/solicitar', [...$this->payload(), 'token' => $token, 'price_minor' => 1])->assertSessionHasNoErrors()->assertRedirect('/pedido/recibido');
        $order = Order::first();
        $this->assertSame(123456, $order->subtotal_minor);
        $this->assertSame(123456, $order->items->first()->unit_price_minor);
        $this->assertSame('received', $order->status);
        $this->assertSame(1, $product->fresh()->stock);
        $this->assertDatabaseCount('jobs', 1);
        $this->assertNotSame($order->customer_email, DB::table('orders')->value('customer_email'));
        $this->assertStringNotContainsString('buyer@example.test', DB::table('jobs')->value('payload'));
        $product->update(['title' => 'Cambio posterior', 'price_minor' => 999999]);
        $this->assertSame(123456, $order->fresh()->subtotal_minor);
        $this->assertNotSame('Cambio posterior', $order->items->first()->title);
        // Retry the exact form with the now-empty cart: one order and one notification.
        $this->post('/solicitar', [...$this->payload(), 'token' => $token])->assertRedirect('/pedido/recibido');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_draft_and_deleted_products_cannot_be_requested(): void
    {
        foreach ([false, true] as $deleted) {
            $product = Product::factory()->create(['is_published' => $deleted]);
            if ($deleted) {
                $product->delete();
            }
            try {
                app(CreateOrder::class)->handle([$product->id => 1], $this->payload(), hash('sha256', Str::uuid()));
                $this->fail('Unavailable product accepted');
            } catch (ValidationException) {
                $this->assertDatabaseCount('orders', 0);
            }
        }
    }

    public function test_published_out_of_stock_and_excess_quantities_create_waiting_requests_with_snapshots(): void
    {
        foreach ([['stock' => 0], ['availability' => 'sold'], ['availability' => 'reserved'], ['stock' => 1]] as $changes) {
            $product = Product::factory()->create([...$changes, 'price_minor' => 25000]);
            $order = app(CreateOrder::class)->handle([$product->id => 2], $this->payload(), hash('sha256', Str::uuid()));
            $this->assertSame('awaiting_stock', $order->status);
            $this->assertTrue($order->items->first()->awaiting_availability);
            $this->assertSame(50000, $order->subtotal_minor);
            $product->update(['stock' => 5, 'availability' => 'available']);
            $this->assertTrue($order->fresh()->items->first()->awaiting_availability);
        }
    }

    public function test_cart_accepts_waiting_quantities_but_keeps_quantity_and_publication_limits(): void
    {
        $product = Product::factory()->create(['stock' => 0]);
        $this->post('/carrito/'.$product->id, ['quantity' => 3])->assertSessionHasNoErrors()->assertSessionHas('cart.'.$product->id, 3);
        $this->post('/carrito/'.$product->id, ['quantity' => 11])->assertSessionHasErrors('quantity');
        $product->update(['is_published' => false]);
        $this->post('/carrito/'.$product->id, ['quantity' => 1])->assertSessionHasErrors('cart');
        $this->post('/carrito/'.$product->id, ['quantity' => 0])->assertSessionHasNoErrors()->assertSessionMissing('cart.'.$product->id);
    }

    public function test_checkout_requires_session_token_shipping_and_consent(): void
    {
        $product = Product::factory()->create();
        $token = (string) Str::uuid();
        $this->withSession(['cart' => [$product->id => 1], 'checkout_token' => $token]);
        $this->post('/solicitar', [...$this->payload(), 'token' => (string) Str::uuid()])->assertStatus(419);
        $this->post('/solicitar', [...$this->payload(), 'token' => $token, 'shipping_address' => '', 'privacy' => false])->assertSessionHasErrors(['shipping_address', 'privacy']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_unknown_price_remains_a_quote_instead_of_zero(): void
    {
        $product = Product::factory()->create(['price_minor' => null, 'availability' => 'inquiry']);
        $order = app(CreateOrder::class)->handle([$product->id => 1], $this->payload(), hash('sha256', Str::uuid()));
        $this->assertNull($order->subtotal_minor);
        $this->assertNull($order->items->first()->unit_price_minor);
    }

    public function test_notification_failure_keeps_order_and_success_omits_customer_pii(): void
    {
        $product = Product::factory()->create();
        $order = app(CreateOrder::class)->handle([$product->id => 1], $this->payload(), hash('sha256', Str::uuid()));
        $job = new NotifyStudio('order', $order->id);
        try {
            $job->handle();
            $this->fail('Missing recipient accepted');
        } catch (\RuntimeException) {
            $this->assertDatabaseHas('orders', ['id' => $order->id, 'notification_status' => 'pending']);
        }
        StudioSetting::current()->update(['contact_email' => 'studio@example.test']);
        Mail::fake();
        $job->handle();
        $job->handle();
        Mail::assertSent(StudioMessage::class, 1);
        $this->assertSame('sent', $order->fresh()->notification_status);
    }

    public function test_customer_records_are_admin_only_and_receipts_are_session_private(): void
    {
        $this->withoutVite();
        $editor = User::factory()->create(['role' => 'editor']);
        $this->actingAs($editor)->get('/admin/orders')->assertForbidden();
        $this->get('/admin/inquiries')->assertForbidden();
        $this->get('/pedido/recibido')->assertNotFound();
        $this->get('/orders/1')->assertNotFound();
    }
}
