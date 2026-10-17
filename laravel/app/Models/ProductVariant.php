<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'stock',
        'sku',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Etiqueta legible de la variante, por ejemplo "M / Negro".
     */
    public function label(): string
    {
        return collect([$this->size, $this->color])->filter()->implode(' / ');
    }

    public function isPurchasable(int $quantity = 1): bool
    {
        return $this->active && $this->stock >= $quantity;
    }
}
