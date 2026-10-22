<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request, Cart $cart)
    {
        return Inertia::render('Cart', ['cart' => $cart->summary($request)]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => 'required|integer|between:0,10']);
        $cart = $request->session()->get('cart', []);
        $quantity = (int) $data['quantity'];
        if ($quantity === 0) {
            unset($cart[$product->id]);
        } else {
            if (! $product->isRequestable() || $quantity > $product->stock || (! isset($cart[$product->id]) && count($cart) >= 20)) {
                throw ValidationException::withMessages(['cart' => 'No es posible añadir esa cantidad de esta obra.']);
            }
            $cart[$product->id] = $quantity;
        }
        $request->session()->put('cart', $cart);
        $request->session()->forget('checkout_token');

        return back()->with('success', $quantity === 0 ? 'Obra retirada de tu selección.' : 'Selección actualizada.');
    }

    public function remove(Request $request, int $id)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$id]);
        $request->session()->put('cart', $cart);
        $request->session()->forget('checkout_token');

        return back()->with('success', 'Obra retirada de tu selección.');
    }
}
