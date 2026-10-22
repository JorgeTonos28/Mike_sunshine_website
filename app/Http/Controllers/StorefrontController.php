<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StorefrontController extends Controller
{
    public function home()
    {
        return Inertia::render('Home', ['products' => Product::with('images')->where('is_published', true)->orderByDesc('is_featured')->latest()->limit(4)->get()->map->publicData()]);
    }

    public function catalog(Request $request, string $kind)
    {
        $filters = $request->validate(['search' => 'nullable|string|max:100', 'category' => 'nullable|string|max:80', 'sort' => 'nullable|in:recent,title,price-asc,price-desc']);
        $query = Product::with('images')->where('is_published', true)->where('kind', $kind);
        $categories = (clone $query)->whereNotNull('category')->distinct()->pluck('category');
        $query->when($filters['search'] ?? null, fn ($q, $term) => $q->where('title', 'like', '%'.$term.'%'))
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->where('category', $category));
        match ($filters['sort'] ?? 'recent') {
            'title' => $query->orderBy('title'),
            'price-asc' => $query->orderByRaw('price_minor IS NULL')->orderBy('price_minor'),
            'price-desc' => $query->orderByRaw('price_minor IS NULL')->orderByDesc('price_minor'),
            default => $query->latest(),
        };

        return Inertia::render('Catalog', ['kind' => $kind, 'products' => $query->paginate(12)->withQueryString()->through(fn ($p) => $p->publicData()), 'categories' => $categories, 'filters' => ['search' => $filters['search'] ?? '', 'category' => $filters['category'] ?? '', 'sort' => $filters['sort'] ?? 'recent']]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_published, 404);

        return Inertia::render('Product', ['product' => $product->load('images')->publicData()]);
    }

    public function contact()
    {
        return Inertia::render('Contact');
    }

    public function legal(string $slug)
    {
        return Inertia::render('Legal', ['page' => LegalPage::where('slug', $slug)->firstOrFail()->only(['title', 'content_blocks', 'is_approved', 'version', 'updated_at'])]);
    }
}
