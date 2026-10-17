<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->getKey();

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('products', 'slug')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'gt:price'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'active' => ['boolean'],
            'featured' => ['boolean'],
            'is_new' => ['boolean'],
            'on_sale' => ['boolean'],
            'sku' => ['nullable', 'string', 'max:60'],
            'tags' => ['nullable', 'string', 'max:255'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.size' => ['nullable', 'string', 'max:30'],
            'variants.*.color' => ['nullable', 'string', 'max:40'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'slug' => 'slug',
            'description' => 'descripción',
            'details' => 'detalles',
            'price' => 'precio',
            'compare_price' => 'precio de comparación',
            'images' => 'imágenes',
            'category_id' => 'categoría',
            'sku' => 'SKU',
            'tags' => 'etiquetas',
            'variants' => 'variantes',
            'variants.*.size' => 'talla',
            'variants.*.color' => 'color',
            'variants.*.stock' => 'stock',
        ];
    }

    public function messages(): array
    {
        return [
            'compare_price.gt' => 'El precio de comparación debe ser mayor que el precio actual.',
            'variants.required' => 'El producto debe tener al menos una variante con stock.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function productAttributes(): array
    {
        $data = $this->safe([
            'name',
            'slug',
            'description',
            'details',
            'price',
            'compare_price',
            'category_id',
            'sku',
        ]);

        $data['images'] = array_values(array_filter($this->input('images', [])));
        $data['tags'] = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) $this->input('tags'))
        )));

        foreach (['active', 'featured', 'is_new', 'on_sale'] as $flag) {
            $data[$flag] = $this->boolean($flag);
        }

        return $data;
    }

    /**
     * @return array<int, array{size: string|null, color: string|null, stock: int, sku: string|null, active: bool}>
     */
    public function variantAttributes(): array
    {
        return collect($this->input('variants', []))
            ->filter(fn (array $variant): bool => filled($variant['size'] ?? null)
                || filled($variant['color'] ?? null)
                || (int) ($variant['stock'] ?? 0) > 0)
            ->map(fn (array $variant): array => [
                'size' => filled($variant['size'] ?? null) ? $variant['size'] : null,
                'color' => filled($variant['color'] ?? null) ? $variant['color'] : null,
                'stock' => (int) ($variant['stock'] ?? 0),
                'sku' => filled($variant['sku'] ?? null) ? $variant['sku'] : null,
                'active' => true,
            ])
            ->values()
            ->all();
    }
}
