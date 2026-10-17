<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Catálogo de demostración: 18 productos con variantes y stock real.
     */
    public function run(): void
    {
        $sizesApparel = ['S', 'M', 'L', 'XL', 'XXL'];
        $sizesShoes = ['37', '38', '39', '40', '41', '42', '43', '44'];

        $catalog = [
            [
                'category' => 'ropa-deportiva',
                'name' => 'Camiseta Dry-Fit Pro',
                'price' => 79.90,
                'compare_price' => 99.90,
                'featured' => true,
                'on_sale' => true,
                'description' => 'Camiseta técnica con tecnología de secado rápido. Ligera, transpirable y perfecta para running, gym o entrenamiento funcional.',
                'details' => "Material: 100% poliéster microfibra\nTecnología Dry-Fit de secado rápido\nCosturas planas que evitan roces\nTalla normal: elige tu talla habitual",
                'variants' => $this->gridVariants($sizesApparel, ['Negro', 'Blanco', 'Naranja'], 12),
            ],
            [
                'category' => 'ropa-deportiva',
                'name' => 'Short Running Elite',
                'price' => 69.90,
                'description' => 'Short de running con interior malla y corte ergonómico. Libre de movimiento en cada zancada.',
                'details' => "Interior: malla de secado rápido\nCorte lateral para mayor movilidad\nCordón ajustable interno",
                'variants' => $this->gridVariants(['S', 'M', 'L', 'XL'], ['Negro', 'Gris'], 10),
            ],
            [
                'category' => 'ropa-deportiva',
                'name' => 'Licra Compresión Full',
                'price' => 89.90,
                'is_new' => true,
                'description' => 'Licra de compresión muscular que mejora el rendimiento y acelera la recuperación post-entrenamiento.',
                'details' => "Compresión graduada de alto rendimiento\nTela four-way stretch\nProtección UV",
                'variants' => $this->gridVariants(['S', 'M', 'L', 'XL'], ['Negro', 'Azul'], 8),
            ],
            [
                'category' => 'ropa-deportiva',
                'name' => 'Casaca Impermeable Trail',
                'price' => 149.90,
                'compare_price' => 199.90,
                'on_sale' => true,
                'featured' => true,
                'description' => 'Casaca impermeable y cortaviento para trail running. Plegable, liviana y con capucha ajustable.',
                'details' => "Impermeable 5K column of water\nCortaviento con forrado interior\nCapucha ajustable y reflectivos",
                'variants' => $this->gridVariants($sizesApparel, ['Negro', 'Naranja'], 7),
            ],
            [
                'category' => 'zapatillas',
                'name' => 'Zapatillas Sprint Speed',
                'price' => 189.90,
                'compare_price' => 239.90,
                'featured' => true,
                'on_sale' => true,
                'description' => 'Zapatillas de velocidad con suela de alta adherencia y amortiguación reactiva para tus mejores tiempos.',
                'details' => "Suela de caucho de alta adherencia\nMediasuela de espuma EVA reactiva\nPeso: 240g aprox. por zapatilla",
                'variants' => $this->gridVariants($sizesShoes, ['Negro/Naranja', 'Blanco/Gris'], 9),
            ],
            [
                'category' => 'zapatillas',
                'name' => 'Zapatillas Cloud Run',
                'price' => 219.90,
                'is_new' => true,
                'description' => 'Máxima amortiguación para largas distancias. Diseñadas para correr como sobre nubes.',
                'details' => "Amortiguación CloudFoam\nMalla transpirable de punto\nDrop: 8mm",
                'variants' => $this->gridVariants($sizesShoes, ['Gris/Blanco', 'Negro'], 6),
            ],
            [
                'category' => 'zapatillas',
                'name' => 'Zapatillas Training X',
                'price' => 169.90,
                'description' => 'Zapatillas versátiles para entrenamiento funcional, crossfit y gym. Estables y duraderas.',
                'details' => "Suela plana y estable para levantamientos\nRefuerzo en talón\nMalla con soporte lateral",
                'variants' => $this->gridVariants(['38', '39', '40', '41', '42', '43'], ['Negro/Rojo'], 11),
            ],
            [
                'category' => 'accesorios',
                'name' => 'Mochila Deportiva 30L',
                'price' => 119.90,
                'featured' => true,
                'description' => 'Mochila deportiva de 30L con compartimento ventilado para zapatos y bolsillo acolchado para laptop.',
                'details' => "Capacidad: 30 litros\nCompartimento ventilado para zapatillas\nBolsillo acolchado para laptop 15\"",
                'variants' => $this->singleVariants(['Negro', 'Naranja'], 15),
            ],
            [
                'category' => 'accesorios',
                'name' => 'Gorra Running Cap',
                'price' => 39.90,
                'description' => 'Gorra liviana con paneles de malla y banda transpirable. Protección UV para tus entrenamientos al aire libre.',
                'details' => "Tela liviana con protección UV\nBanda transpirable interna\nAjuste trasero de velcro",
                'variants' => $this->singleVariants(['Negro', 'Blanco', 'Naranja'], 20),
            ],
            [
                'category' => 'accesorios',
                'name' => 'Medias Deportivas Pack x3',
                'price' => 29.90,
                'compare_price' => 39.90,
                'on_sale' => true,
                'description' => 'Pack de 3 pares de medias deportivas con zona de arco de soporte y secado rápido.',
                'details' => "Pack de 3 pares\nZona de arco de soporte\nTela de secado rápido",
                'variants' => $this->singleVariants(['Único'], 40),
            ],
            [
                'category' => 'accesorios',
                'name' => 'Botella Térmica 750ml',
                'price' => 49.90,
                'is_new' => true,
                'description' => 'Botella térmica de acero inoxidable que mantiene frío por 24 horas o caliente por 12 horas.',
                'details' => "Acero inoxidable de doble pared\nLibre de BPA\nTapa hermética con asa",
                'variants' => $this->singleVariants(['Negro', 'Blanco'], 25),
            ],
            [
                'category' => 'entrenamiento',
                'name' => 'Guantes Training Pro',
                'price' => 59.90,
                'description' => 'Guantes de entrenamiento con acolchado de gel y agarre antideslizante para pesas y máquinas.',
                'details' => "Acolchado de gel en la palma\nAntideslizantes\nCierre de velcro ajustable",
                'variants' => $this->singleVariants(['S/M', 'L/XL'], 14),
            ],
            [
                'category' => 'entrenamiento',
                'name' => 'Bandas de Resistencia Set',
                'price' => 45.90,
                'featured' => true,
                'description' => 'Set de 5 bandas de resistencia con distintos niveles. Ideales para movilidad, calentamiento y fuerza.',
                'details' => "5 niveles de resistencia\nLátex natural de alta elasticidad\nIncluye bolso de transporte",
                'variants' => $this->singleVariants(['Único'], 30),
            ],
            [
                'category' => 'entrenamiento',
                'name' => 'Mat de Yoga Antideslizante',
                'price' => 79.90,
                'compare_price' => 99.90,
                'on_sale' => true,
                'description' => 'Mat de yoga de 6mm con superficie antideslizante y absorción de sudor. Perfecta para yoga y estiramiento.',
                'details' => "Espesor: 6mm\nSuperficie TPE antideslizante\nDimensiones: 183 x 61 cm",
                'variants' => $this->singleVariants(['Gris', 'Naranja'], 12),
            ],
            [
                'category' => 'entrenamiento',
                'name' => 'Chaleco de Pesas 10kg',
                'price' => 249.90,
                'compare_price' => 299.90,
                'on_sale' => true,
                'is_new' => true,
                'description' => 'Chaleco ajustable de 10kg con pesas distribuidas. Multiplica el esfuerzo de caminatas, sprints y calistenia.',
                'details' => "Peso total: 10kg ajustable\nCorreas acolchadas antideslizantes\nIdeal para running y calistenia",
                'variants' => $this->singleVariants(['M', 'L'], 5),
            ],
            [
                'category' => 'equipamiento',
                'name' => 'Balón Fútbol Sala Pro',
                'price' => 89.90,
                'featured' => true,
                'description' => 'Balón de fútbol sala con costura reforzada y agarre ideal para cancha techada o callejera.',
                'details' => "Tamaños: 3, 4 y 5\nCostura reforzada\nAgarre óptimo en superficies duras",
                'variants' => $this->gridVariants(['3', '4', '5'], ['Naranja/Blanco', 'Negro'], 10),
            ],
            [
                'category' => 'equipamiento',
                'name' => 'Rodilleras Fútbol Par',
                'price' => 55.90,
                'description' => 'Par de rodilleras acolchadas con ajuste elástico. Protección cómoda en entrenamientos y partidos.',
                'details' => "Acolchado de alta densidad\nBanda elástica de sujeción\nTalla única ajustable",
                'variants' => $this->singleVariants(['Único'], 20),
            ],
            [
                'category' => 'equipamiento',
                'name' => 'Shin Guards Pro',
                'price' => 45.90,
                'is_new' => true,
                'description' => 'Espinilleras ligeras con espuma de alta protección. Seguridad sin sacrificar la movilidad.',
                'details' => "Espuma de alta impactación\nForro interior acolchado\nIncluye medias de sujeción",
                'variants' => $this->gridVariants(['S', 'M', 'L'], ['Blanco', 'Negro'], 12),
            ],
        ];

        foreach ($catalog as $item) {
            $category = Category::query()->where('slug', $item['category'])->firstOrFail();
            $slug = Str::slug($item['name']);

            $product = Product::query()->create([
                'name' => $item['name'],
                'slug' => $slug,
                'description' => $item['description'],
                'details' => $item['details'] ?? null,
                'price' => $item['price'],
                'compare_price' => $item['compare_price'] ?? null,
                'images' => ["https://picsum.photos/seed/{$slug}/700/700"],
                'category_id' => $category->id,
                'active' => true,
                'featured' => $item['featured'] ?? false,
                'is_new' => $item['is_new'] ?? false,
                'on_sale' => $item['on_sale'] ?? false,
                'sku' => strtoupper(Str::upper(Str::random(2))).'-'.random_int(1000, 9999),
                'tags' => ['deporte', 'forcha'],
            ]);

            $product->variants()->createMany($item['variants']);
        }
    }

    /**
     * @param  array<int, string>  $sizes
     * @param  array<int, string>  $colors
     * @return array<int, array{size: string, color: string, stock: int, active: bool}>
     */
    private function gridVariants(array $sizes, array $colors, int $baseStock): array
    {
        $variants = [];

        foreach ($sizes as $size) {
            foreach ($colors as $color) {
                $variants[] = [
                    'size' => $size,
                    'color' => $color,
                    'stock' => random_int(max(0, $baseStock - 4), $baseStock + 4),
                    'active' => true,
                ];
            }
        }

        return $variants;
    }

    /**
     * @param  array<int, string>  $colors
     * @return array<int, array{size: string|null, color: string, stock: int, active: bool}>
     */
    private function singleVariants(array $colors, int $baseStock): array
    {
        return array_map(fn (string $color): array => [
            'size' => null,
            'color' => $color,
            'stock' => random_int(max(0, $baseStock - 5), $baseStock + 5),
            'active' => true,
        ], $colors);
    }
}
