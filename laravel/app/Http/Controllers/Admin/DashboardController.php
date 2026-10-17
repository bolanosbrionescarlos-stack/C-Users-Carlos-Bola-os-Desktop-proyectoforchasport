<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'products' => Product::query()->active()->count(),
            'orders' => Order::query()->count(),
            'pending_orders' => Order::query()->where('order_status', OrderStatus::Pending)->count(),
            'revenue' => Order::query()
                ->whereNot('order_status', OrderStatus::Cancelled)
                ->sum('total'),
            'coupons' => Coupon::query()->active()->count(),
        ];

        $recentOrders = Order::query()->latest()->limit(8)->get();

        $lowStock = ProductVariant::query()
            ->active()
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->where('stock', '<=', 5)
            ->with('product')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStock'));
    }
}
