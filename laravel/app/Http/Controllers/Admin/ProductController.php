<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with('category')->latest();

        if ($search = $request->query('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $request->query('categoria')));
        }

        if ($request->boolean('inactive')) {
            $query->where('active', false);
        }

        $products = $query->paginate(20)->withQueryString();

        $categories = Category::query()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $product = Product::query()->create($request->productAttributes());

            $product->variants()->createMany($request->variantAttributes());
        });

        return redirect()
            ->route('admin.productos.index')
            ->with('status', 'Producto creado correctamente.');
    }

    public function edit(Product $product): View
    {
        $product->load('variants');

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product, $request) {
            $product->update($request->productAttributes());

            $product->variants()->delete();
            $product->variants()->createMany($request->variantAttributes());
        });

        return redirect()
            ->route('admin.productos.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.productos.index')
            ->with('status', 'Producto eliminado.');
    }
}
