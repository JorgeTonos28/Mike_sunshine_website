<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class Cart
{
    public function summary(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::with('images')->where('is_published', true)->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = [];
        $subtotal = 0;
        $allPriced = count($cart) > 0;
        foreach ($cart as $id => $quantity) {
            $product = $products->get($id);
            $items[] = ['product_id' => (int) $id, 'quantity' => $quantity, 'product' => $product?->publicData(), 'max_quantity' => $product ? min(10, $product->stock) : 0];
            $allPriced = $allPriced && $product && $product->price_minor !== null;
            $subtotal += ($product?->price_minor ?? 0) * $quantity;
        }

        return ['items' => $items, 'subtotal_minor' => $allPriced ? $subtotal : null];
    }
}
