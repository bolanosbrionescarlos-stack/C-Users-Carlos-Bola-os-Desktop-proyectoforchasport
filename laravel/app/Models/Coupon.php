<?php

namespace App\Models;

use App\Enums\CouponType;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'max_uses',
        'used_count',
        'active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'value' => 'decimal:2',
            'min_order' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Motivo por el cual el cupón no puede usarse, o null si es válido.
     */
    public function unavailabilityReasonFor(float $subtotal): ?string
    {
        if (! $this->active) {
            return 'Este cupón ya no está disponible.';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Este cupón ha expirado.';
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 'Este cupón alcanzó el límite de usos.';
        }

        if ($subtotal < (float) $this->min_order) {
            return 'Este cupón requiere un pedido mínimo de S/ '.number_format((float) $this->min_order, 2).'.';
        }

        return null;
    }

    /**
     * Monto de descuento que aplica sobre el subtotal dado.
     */
    public function discountFor(float $subtotal): float
    {
        $discount = match ($this->type) {
            CouponType::Percent => $subtotal * ((float) $this->value / 100),
            CouponType::Fixed => (float) $this->value,
        };

        return round(min($discount, $subtotal), 2);
    }

    /**
     * Descripción corta del beneficio, para mostrar en el carrito.
     */
    public function description(): string
    {
        return match ($this->type) {
            CouponType::Percent => '-'.rtrim(rtrim(number_format((float) $this->value, 2), '0'), '.').'%',
            CouponType::Fixed => '-S/ '.number_format((float) $this->value, 2),
        };
    }
}
