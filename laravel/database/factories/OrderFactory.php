<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 500);
        $shipping = fake()->randomElement([10.0, 15.0, 20.0]);

        return [
            'code' => 'FS-'.strtoupper(Str::lower(fake()->unique()->bothify('######-#####'))),
            'customer_name' => fake()->name(),
            'customer_phone' => '9'.fake()->numerify('########'),
            'customer_email' => fake()->boolean(50) ? fake()->safeEmail() : null,
            'department' => 'Lima',
            'province' => 'Lima',
            'district' => fake()->randomElement(['Miraflores', 'Surco', 'La Molina', 'San Isidro', 'Callao']),
            'address' => fake()->streetAddress(),
            'reference' => fake()->boolean() ? fake()->streetName() : null,
            'shipping_method' => fake()->randomElement(['lima', 'national', 'express']),
            'shipping_cost' => $shipping,
            'payment_method' => fake()->randomElement(['yape', 'transfer', 'cod']),
            'payment_status' => fake()->randomElement(PaymentStatus::cases()),
            'order_status' => fake()->randomElement(OrderStatus::cases()),
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal + $shipping,
            'coupon_code' => null,
            'payment_ref' => null,
            'notes' => fake()->boolean() ? fake()->sentence() : null,
        ];
    }
}
