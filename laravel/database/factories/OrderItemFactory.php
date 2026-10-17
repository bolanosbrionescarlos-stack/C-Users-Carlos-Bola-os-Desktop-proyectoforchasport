<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::query()->inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => fake()->numberBetween(1, 3),
            'size' => fake()->boolean() ? fake()->randomElement(['S', 'M', 'L']) : null,
            'color' => fake()->boolean() ? fake()->randomElement(['Negro', 'Blanco']) : null,
            'image' => $product->mainImage(),
        ];
    }
}
