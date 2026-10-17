<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Carrito de compras basado en sesión.
 *
 * Solo se guardan identificadores y cantidades en la sesión; los precios y
 * stock siempre se leen de base de datos para evitar datos desactualizados.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    private const COUPON_KEY = 'coupon';

    /**
     * Líneas del carrito con producto, variante y totales ya resueltos.
     *
     * @return Collection<int, array{key: string, product: Product, variant: ProductVariant|null, quantity: int, unit_price: float, subtotal: float, available_stock: int}>
     */
    public function lines(): Collection
    {
        return collect($this->rawLines())
            ->map(function (array $line) {
                $product = Product::query()
                    ->with('category')
                    ->active()
                    ->find($line['product_id']);

                if (! $product) {
                    return null;
                }

                $variant = $product->variants->firstWhere('id', $line['variant_id'])
                    ?? $product->activeVariants->first();

                if (! $variant) {
                    return null;
                }

                $quantity = min((int) $line['quantity'], max($variant->stock, 0));

                return [
                    'key' => $this->lineKey($product->id, $variant->id),
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => (float) $product->price,
                    'subtotal' => (float) $product->price * $quantity,
                    'available_stock' => (int) $variant->stock,
                ];
            })
            ->filter()
            ->values();
    }

    public function add(int $productId, ?int $variantId = null, int $quantity = 1): void
    {
        $product = Product::query()->active()->findOrFail($productId);

        $variant = $variantId
            ? $product->activeVariants()->whereKey($variantId)->firstOrFail()
            : $product->activeVariants()->firstOrFail();

        if ($variant->stock < 1) {
            throw ValidationException::withMessages([
                'cart' => "No hay stock disponible para {$product->name}".($variant->label() ? " ({$variant->label()})" : '').'.',
            ]);
        }

        $lines = $this->rawLines();
        $key = $this->lineKey($product->id, $variant->id);

        if (isset($lines[$key])) {
            $lines[$key]['quantity'] = min($lines[$key]['quantity'] + $quantity, $variant->stock);
        } else {
            $lines[$key] = [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'quantity' => min($quantity, $variant->stock),
            ];
        }

        $this->persist($lines);
    }

    public function update(string $key, int $quantity): void
    {
        $lines = $this->rawLines();

        if (! isset($lines[$key])) {
            return;
        }

        if ($quantity <= 0) {
            unset($lines[$key]);
        } else {
            $variant = ProductVariant::query()->find($lines[$key]['variant_id']);
            $lines[$key]['quantity'] = $variant ? min($quantity, $variant->stock) : $quantity;
        }

        $this->persist($lines);
    }

    public function remove(string $key): void
    {
        $lines = $this->rawLines();

        unset($lines[$key]);

        $this->persist($lines);
    }

    public function clear(): void
    {
        session()->forget([self::SESSION_KEY, self::COUPON_KEY]);
    }

    public function isEmpty(): bool
    {
        return $this->rawLines() === [];
    }

    public function totalItems(): int
    {
        return (int) collect($this->rawLines())->sum('quantity');
    }

    public function subtotal(): float
    {
        return round($this->lines()->sum('subtotal'), 2);
    }

    /**
     * Costo de envío para el método indicado, con envío gratis si aplica.
     */
    public function shippingCost(string $method): float
    {
        $price = config("store.shipping_methods.{$method}.price");

        if ($price === null) {
            return 0.0;
        }

        $freeOver = (float) config('store.free_shipping_over', 0);

        if ($freeOver > 0 && $this->subtotal() >= $freeOver) {
            return 0.0;
        }

        return (float) $price;
    }

    public function coupon(): ?Coupon
    {
        $code = session(self::COUPON_KEY);

        if (! $code) {
            return null;
        }

        $coupon = Coupon::query()->where('code', $code)->first();

        if (! $coupon || $coupon->unavailabilityReasonFor($this->subtotal())) {
            session()->forget(self::COUPON_KEY);

            return null;
        }

        return $coupon;
    }

    /**
     * Aplica un cupón al carrito. Devuelve null si quedó bien, o el motivo del error.
     */
    public function applyCoupon(string $code): ?string
    {
        $coupon = Coupon::query()->where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            return 'El cupón ingresado no existe.';
        }

        $reason = $coupon->unavailabilityReasonFor($this->subtotal());

        if ($reason) {
            return $reason;
        }

        session()->put(self::COUPON_KEY, $coupon->code);

        return null;
    }

    public function removeCoupon(): void
    {
        session()->forget(self::COUPON_KEY);
    }

    public function discount(): float
    {
        $coupon = $this->coupon();

        return $coupon ? $coupon->discountFor($this->subtotal()) : 0.0;
    }

    /**
     * @return array<string, array{product_id: int, variant_id: int, quantity: int}>
     */
    private function rawLines(): array
    {
        return array_filter(session(self::SESSION_KEY, []), 'is_array');
    }

    /**
     * @param  array<string, array{product_id: int, variant_id: int, quantity: int}>  $lines
     */
    private function persist(array $lines): void
    {
        session()->put(self::SESSION_KEY, $lines);
    }

    private function lineKey(int $productId, int $variantId): string
    {
        return $productId.'-'.$variantId;
    }
}
