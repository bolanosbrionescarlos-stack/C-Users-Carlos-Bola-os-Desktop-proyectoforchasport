<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orders,
    ) {}

    public function create(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Tu carrito está vacío.');
        }

        return view('store.checkout.create', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'discount' => $this->cart->discount(),
            'coupon' => $this->cart->coupon(),
            'departments' => config('store.departments'),
            'shippingMethods' => config('store.shipping_methods'),
            'paymentMethods' => config('store.payment_methods'),
            'freeShippingOver' => config('store.free_shipping_over'),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->orders->place($request->validated());

        return redirect()
            ->route('orders.show', $order)
            ->with('status', "¡Pedido {$order->code} registrado! Te contactaremos por WhatsApp para confirmar.");
    }
}
