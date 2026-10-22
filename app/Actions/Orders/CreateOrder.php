<?php

namespace App\Actions\Orders;

use App\Jobs\NotifyStudio;
use App\Models\LegalPage;
use App\Models\Order;
use App\Models\Product;
use App\Models\StudioSetting;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateOrder
{
    public function handle(array $cart, array $data, string $hash): Order
    {
        try {
            return DB::transaction(function () use ($cart, $data, $hash) {
                if ($existing = Order::where('submission_hash', $hash)->first()) {
                    return $existing;
                }
                if (! $cart || count($cart) > 20) {
                    throw ValidationException::withMessages(['cart' => 'Añade entre 1 y 20 obras a tu selección.']);
                }
                $products = Product::whereIn('id', array_keys($cart))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $subtotal = 0;
                $allPriced = true;
                $awaitingAvailability = false;
                $currency = StudioSetting::current()->currency;
                foreach ($cart as $id => $quantity) {
                    $product = $products->get($id);
                    if (! $product || ! $product->isRequestable() || ! is_int($quantity) || $quantity < 1 || $quantity > 10) {
                        throw ValidationException::withMessages(['cart' => 'La selección ha cambiado o una obra fue retirada del catálogo. Revisa el carrito.']);
                    }
                    if ($product->price_minor !== null && $product->currency !== $currency) {
                        throw ValidationException::withMessages(['cart' => 'La moneda de una obra ha cambiado. Contacta con el estudio.']);
                    }
                    $allPriced = $allPriced && $product->price_minor !== null;
                    $awaitingAvailability = $awaitingAvailability || $product->requiresAvailability($quantity);
                    $subtotal += ($product->price_minor ?? 0) * $quantity;
                }
                $order = Order::create([
                    'reference' => 'MS-'.Str::upper(Str::random(12)), 'submission_hash' => $hash, 'status' => $awaitingAvailability ? 'awaiting_stock' : 'received',
                    'customer_name' => $data['customer_name'], 'customer_email' => $data['customer_email'],
                    'customer_phone' => $data['customer_phone'] ?? null,
                    'shipping_address' => $data['delivery_method'] === 'shipping' ? $data['shipping_address'] : null,
                    'delivery_method' => $data['delivery_method'], 'message' => $data['message'] ?? null,
                    'currency' => $currency, 'subtotal_minor' => $allPriced ? $subtotal : null,
                    'privacy_accepted_at' => now(), 'terms_version' => LegalPage::where('slug', 'terminos')->value('version') ?? 1,
                    'notification_status' => 'pending',
                ]);
                foreach ($cart as $id => $quantity) {
                    $product = $products[$id];
                    $order->items()->create(['product_id' => $id, 'title' => $product->title, 'quantity' => $quantity, 'unit_price_minor' => $product->price_minor, 'currency' => $product->currency, 'awaiting_availability' => $product->requiresAvailability($quantity)]);
                }
                // Database queue and order use the same connection: either both commit or neither does.
                NotifyStudio::dispatch('order', $order->id)->onConnection('database')->beforeCommit();

                return $order;
            }, 3);
        } catch (UniqueConstraintViolationException $exception) {
            return Order::where('submission_hash', $hash)->first() ?? throw $exception;
        }
    }
}
