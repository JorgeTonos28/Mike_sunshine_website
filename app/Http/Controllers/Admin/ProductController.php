<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Catalog\SaveProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = mb_substr((string) $request->query('search', ''), 0, 100);

        return Inertia::render('admin/Products', [
            'products' => Product::with('images')->when($search, fn ($q) => $q->where('title', 'like', '%'.$search.'%'))->latest()->paginate(20)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/ProductForm', ['product' => null]);
    }

    public function store(ProductRequest $request, SaveProduct $action)
    {
        $product = $action->handle(new Product, $request->validated(), $request->file('uploads', []));

        return to_route('admin.products.edit', $product)->with('success', 'Obra creada.');
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        return Inertia::render('admin/ProductForm', ['product' => $product->load('images')]);
    }

    public function update(ProductRequest $request, Product $product, SaveProduct $action)
    {
        Gate::authorize('update', $product);
        $action->handle($product, $request->validated(), $request->file('uploads', []));

        return back()->with('success', 'Obra actualizada.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $product->delete();

        return to_route('admin.products.index')->with('success', 'Obra archivada. Se conservan sus solicitudes anteriores.');
    }
}
