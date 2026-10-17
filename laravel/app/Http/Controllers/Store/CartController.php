<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('store.cart.index', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'discount' => $this->cart->discount(),
            'coupon' => $this->cart->coupon(),
            'couponError' => session('coupon_error'),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $this->cart->add(
            (int) $data['product_id'],
            isset($data['variant_id']) ? (int) $data['variant_id'] : null,
            (int) ($data['quantity'] ?? 1),
        );

        return redirect()
            ->route('cart.index')
            ->with('status', 'Producto agregado al carrito.');
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $this->cart->update($key, (int) $data['quantity']);

        return back()->with('status', 'Carrito actualizado.');
    }

    public function remove(string $key): RedirectResponse
    {
        $this->cart->remove($key);

        return back()->with('status', 'Producto eliminado del carrito.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'coupon' => ['required', 'string', 'max:40'],
        ]);

        $error = $this->cart->applyCoupon($data['coupon']);

        if ($error) {
            return back()->withInput()->withErrors(['coupon' => $error]);
        }

        return back()->with('status', 'Cupón aplicado correctamente.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cart->removeCoupon();

        return back()->with('status', 'Cupón removido.');
    }
}
