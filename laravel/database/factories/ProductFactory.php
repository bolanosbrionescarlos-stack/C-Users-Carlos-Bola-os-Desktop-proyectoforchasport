<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(3, true));
        $slug = Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999);
        $price = fake()->randomFloat(2, 29.9, 299.9);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->paragraph(),
            'details' => fake()->paragraphs(2, true),
            'price' => $price,
            'compare_price' => fake()->boolean(30) ? round($price * 1.3, 2) : null,
            'images' => ["https://picsum.photos/seed/{$slug}/700/700"],
            'category_id' => Category::factory(),
            'active' => true,
            'featured' => fake()->boolean(20),
            'is_new' => fake()->boolean(20),
            'on_sale' => fake()->boolean(20),
            'sku' => strtoupper(fake()->bothify('FS-####??')),
            'tags' => fake()->randomElements(['deporte', 'running', 'entrenamiento', 'outdoor', 'premium'], 2),
        ];
    }
}
