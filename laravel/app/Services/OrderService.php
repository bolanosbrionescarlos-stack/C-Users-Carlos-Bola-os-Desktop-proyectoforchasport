<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Lógica de creación de pedidos a partir del carrito de compras.
 */
class OrderService
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * Registra el pedido descargando stock de forma transaccional.
     *
     * @param  array{
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_email?: string|null,
     *     department: string,
     *     province: string,
     *     district: string,
     *     address: string,
     *     reference?: string|null,
     *     shipping_method: string,
     *     payment_method: string,
     *     payment_ref?: string|null,
     *     notes?: string|null,
     * }  $customer
     */
    public function place(array $customer): Order
    {
        if ($this->cart->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Tu carrito está vacío.',
            ]);
        }

        return DB::transaction(function () use ($customer) {
            $subtotal = $this->cart->subtotal();
            $discount = $this->cart->discount();
            $coupon = $this->cart->coupon();
            $shippingCost = $this->cart->shippingCost($customer['shipping_method']);

            $order = Order::query()->create([
                'code' => $this->generateCode(),
                'customer_name' => $customer['customer_name'],
                'customer_phone' => $customer['customer_phone'],
                'customer_email' => $customer['customer_email'] ?? null,
                'department' => $customer['department'],
                'province' => $customer['province'],
                'district' => $customer['district'],
                'address' => $customer['address'],
                'reference' => $customer['reference'] ?? null,
                'shipping_method' => $customer['shipping_method'],
                'shipping_cost' => $shippingCost,
                'payment_method' => $customer['payment_method'],
                'payment_status' => PaymentStatus::Pending,
                'order_status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => max(0, $subtotal - $discount) + $shippingCost,
                'coupon_code' => $coupon?->code,
                'payment_ref' => $customer['payment_ref'] ?? null,
                'notes' => $customer['notes'] ?? null,
            ]);

            foreach ($this->cart->lines() as $line) {
                /** @var ProductVariant $variant */
                $variant = ProductVariant::query()->lockForUpdate()->findOrFail($line['variant']->id);

                if ($line['quantity'] < 1 || $variant->stock < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "No hay stock disponible para {$line['product']->name}"
                            .($variant->label() ? " ({$variant->label()})" : '').'.',
                    ]);
                }

                $variant->decrement('stock', $line['quantity']);

                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'image' => $line['product']->mainImage(),
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $this->cart->clear();

            return $order->load('items');
        });
    }

    private function generateCode(): string
    {
        do {
            $code = 'FS-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (Order::query()->where('code', $code)->exists());

        return $code;
    }
}
