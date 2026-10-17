<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Catálogo con filtros de búsqueda, categoría, precio y orden.
     */
    public function index(Request $request): View
    {
        $perPage = (int) config('store.page_size', 16);

        $query = Product::query()->active()->with('category');

        if ($search = $request->query('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->query('categoria')) {
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $categorySlug));
        }

        if ($request->boolean('onSale')) {
            $query->onSale();
        }

        if ($request->boolean('isNew')) {
            $query->new();
        }

        if ($request->filled('minPrice')) {
            $query->where('price', '>=', (float) $request->query('minPrice'));
        }

        if ($request->filled('maxPrice')) {
            $query->where('price', '<=', (float) $request->query('maxPrice'));
        }

        match ($request->query('orderBy')) {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate($perPage)->withQueryString();

        $categories = Category::query()->active()->ordered()->get();

        $currentCategory = $categories->firstWhere('slug', $categorySlug);

        return view('store.products.index', compact('products', 'categories', 'currentCategory'));
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->active()
            ->with(['category', 'activeVariants'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::query()
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->with('category')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('store.products.show', compact('product', 'related'));
    }
}
