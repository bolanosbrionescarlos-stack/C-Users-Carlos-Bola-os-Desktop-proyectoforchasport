<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Categorías principales de la tienda.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ropa Deportiva',
                'slug' => 'ropa-deportiva',
                'description' => 'Camisetas, shorts, licras y más',
                'icon' => '👕',
                'color' => '#FF5500',
                'sort_order' => 1,
            ],
            [
                'name' => 'Zapatillas',
                'slug' => 'zapatillas',
                'description' => 'Running, entrenamiento y lifestyle',
                'icon' => '👟',
                'color' => '#1A1A1A',
                'sort_order' => 2,
            ],
            [
                'name' => 'Accesorios',
                'slug' => 'accesorios',
                'description' => 'Mochilas, gorras, medias y más',
                'icon' => '🎒',
                'color' => '#FF7A00',
                'sort_order' => 3,
            ],
            [
                'name' => 'Entrenamiento',
                'slug' => 'entrenamiento',
                'description' => 'Equipamiento para gym y fitness',
                'icon' => '🏋️',
                'color' => '#222222',
                'sort_order' => 4,
            ],
            [
                'name' => 'Equipamiento',
                'slug' => 'equipamiento',
                'description' => 'Balones, protecciones y más',
                'icon' => '⚽',
                'color' => '#CC4400',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }
    }
}
