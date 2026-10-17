<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(2, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->sentence(6),
            'image' => null,
            'icon' => fake()->randomElement(['👕', '👟', '🎒', '🏋️', '⚽']),
            'color' => fake()->hexColor(),
            'active' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
