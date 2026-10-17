<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()->withCount('items')->latest();

        if ($search = $request->query('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('order_status', $request->query('estado'));
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order' => $order,
            'shippingMethods' => config('store.shipping_methods'),
            'paymentMethods' => config('store.payment_methods'),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'order_status' => ['required', 'string', 'in:'.implode(',', array_column(OrderStatus::cases(), 'value'))],
        ]);

        $order->update(['order_status' => $data['order_status']]);

        return back()->with('status', 'Estado del pedido actualizado.');
    }

    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'payment_status' => ['required', 'string', 'in:'.implode(',', array_column(PaymentStatus::cases(), 'value'))],
            'payment_ref' => ['nullable', 'string', 'max:120'],
        ]);

        $order->update([
            'payment_status' => $data['payment_status'],
            'payment_ref' => $data['payment_ref'] ?? $order->payment_ref,
        ]);

        return back()->with('status', 'Estado de pago actualizado.');
    }
}
