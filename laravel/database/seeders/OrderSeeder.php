<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Pedidos de demostración para probar el panel de administración.
     */
    public function run(): void
    {
        $statuses = [
            [OrderStatus::Pending, PaymentStatus::Pending],
            [OrderStatus::Confirmed, PaymentStatus::Paid],
            [OrderStatus::Shipped, PaymentStatus::Paid],
            [OrderStatus::Delivered, PaymentStatus::Paid],
        ];

        foreach ($statuses as [$orderStatus, $paymentStatus]) {
            $product = Product::query()->active()->inRandomOrder()->first();
            $variant = $product->activeVariants()->inRandomOrder()->first();
            $quantity = random_int(1, 2);
            $subtotal = round((float) $product->price * $quantity, 2);

            $order = Order::query()->create([
                'code' => 'FS-'.now()->format('ymd').'-'.Str::upper(Str::random(5)),
                'customer_name' => fake()->name(),
                'customer_phone' => '9'.fake()->numerify('########'),
                'customer_email' => fake()->boolean(60) ? fake()->safeEmail() : null,
                'department' => 'Lima',
                'province' => 'Lima',
                'district' => fake()->randomElement(['Miraflores', 'Surco', 'La Molina', 'San Borja']),
                'address' => fake()->streetAddress(),
                'reference' => fake()->boolean() ? fake()->streetName() : null,
                'shipping_method' => fake()->randomElement(['lima', 'express']),
                'shipping_cost' => fake()->randomElement([10.0, 20.0]),
                'payment_method' => fake()->randomElement(['yape', 'transfer', 'cod']),
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,
                'subtotal' => $subtotal,
                'discount' => 0,
                'total' => $subtotal + fake()->randomElement([10.0, 20.0]),
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'size' => $variant->size,
                'color' => $variant->color,
                'image' => $product->mainImage(),
            ]);
        }
    }
}
