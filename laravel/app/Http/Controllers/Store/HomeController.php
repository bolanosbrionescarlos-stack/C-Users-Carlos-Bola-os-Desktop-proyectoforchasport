<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::query()
            ->active()
            ->ordered()
            ->get();

        $featured = Product::query()
            ->active()
            ->featured()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        $newProducts = Product::query()
            ->active()
            ->new()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        $onSale = Product::query()
            ->active()
            ->onSale()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        return view('store.home', compact('categories', 'featured', 'newProducts', 'onSale'));
    }
}
