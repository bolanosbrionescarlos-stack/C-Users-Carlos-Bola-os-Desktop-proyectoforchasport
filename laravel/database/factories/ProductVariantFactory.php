<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'color' => fake()->randomElement(['Negro', 'Blanco', 'Naranja', 'Gris']),
            'stock' => fake()->numberBetween(0, 30),
            'sku' => strtoupper(fake()->bothify('FS-V####??')),
            'active' => true,
        ];
    }
}
