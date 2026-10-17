<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'details',
        'price',
        'compare_price',
        'images',
        'category_id',
        'active',
        'featured',
        'is_new',
        'on_sale',
        'sku',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'images' => 'array',
            'tags' => 'array',
            'active' => 'boolean',
            'featured' => 'boolean',
            'is_new' => 'boolean',
            'on_sale' => 'boolean',
        ];
    }

    /**
     * Las rutas de producto usan el slug, no el id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->variants()->where('active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where('on_sale', true);
    }

    /**
     * Imagen principal del producto, si tiene.
     */
    public function mainImage(): ?string
    {
        return $this->images[0] ?? null;
    }

    /**
     * Porcentaje de descuento respecto al precio de comparación.
     */
    public function discountPercent(): int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    /**
     * Stock total sumando todas sus variantes activas.
     */
    public function totalStock(): int
    {
        return (int) $this->variants->where('active', true)->sum('stock');
    }

    public function hasStock(): bool
    {
        return $this->totalStock() > 0;
    }
}
