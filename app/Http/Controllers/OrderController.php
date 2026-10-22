<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CreateOrder;
use App\Http\Requests\OrderRequest;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function create(Request $request, Cart $cart)
    {
        if (! $request->session()->get('cart', [])) {
            return to_route('cart');
        }
        $token = $request->session()->get('checkout_token', (string) Str::uuid());
        $request->session()->put('checkout_token', $token);

        return Inertia::render('Checkout', ['cart' => $cart->summary($request), 'token' => $token]);
    }

    public function store(OrderRequest $request, CreateOrder $action)
    {
        abort_unless(hash_equals((string) $request->session()->get('checkout_token', ''), $request->validated('token')), 419);
        $hash = hash('sha256', $request->validated('token'));
        $order = $action->handle($request->session()->get('cart', []), $request->validated(), $hash);
        $request->session()->put('receipt', $order->reference);
        $request->session()->forget('cart');

        return to_route('order.receipt');
    }

    public function receipt(Request $request)
    {
        abort_unless($request->session()->has('receipt'), 404);

        return Inertia::render('Receipt', ['reference' => $request->session()->get('receipt')]);
    }
}
