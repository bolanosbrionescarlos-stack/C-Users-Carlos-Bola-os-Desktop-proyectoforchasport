<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function productWithStock(int $stock = 10): Product
    {
        $product = Product::factory()->create(['active' => true, 'price' => 100]);

        $product->variants()->create(['size' => 'M', 'color' => 'Negro', 'stock' => $stock, 'active' => true]);

        return $product->load('variants');
    }

    public function test_product_can_be_added_to_cart(): void
    {
        $product = $this->productWithStock();

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'variant_id' => $product->variants->first()->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('cart');

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_cart_quantity_can_be_updated_and_line_removed(): void
    {
        $product = $this->productWithStock();
        $variant = $product->variants->first();
        $key = $product->id.'-'.$variant->id;

        $this->withSession(['cart' => [$key => ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 3]]])
            ->patch(route('cart.update', $key), ['quantity' => 1])
            ->assertRedirect();

        $this->withSession(['cart' => [$key => ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 1]]])
            ->delete(route('cart.remove', $key))
            ->assertRedirect();

        $this->withSession(['cart' => []])
            ->get(route('cart.index'))
            ->assertSee('Tu carrito está vacío');
    }

    public function test_cart_quantity_is_capped_by_stock(): void
    {
        $product = $this->productWithStock(stock: 2);
        $variant = $product->variants->first();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 20,
        ]);

        $cart = $this->app->make(CartService::class);

        $this->assertSame(2, $cart->lines()->first()['quantity']);
    }

    public function test_invalid_coupon_shows_error_and_valid_coupon_applies(): void
    {
        $product = $this->productWithStock();
        $variant = $product->variants->first();
        $key = $product->id.'-'.$variant->id;
        $session = ['cart' => [$key => ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 1]]];

        Coupon::factory()->create(['code' => 'BIENVENIDO10', 'type' => 'percent', 'value' => 10, 'min_order' => 0, 'active' => true]);

        $this->withSession($session)
            ->post(route('cart.coupon.apply'), ['coupon' => 'NOEXISTE'])
            ->assertSessionHasErrors('coupon');

        $this->withSession($session)
            ->post(route('cart.coupon.apply'), ['coupon' => 'bIeNvEnIdO10'])
            ->assertSessionHasNoErrors();

        $this->withSession($session + ['coupon' => 'BIENVENIDO10'])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('BIENVENIDO10');
    }

    public function test_coupon_minimum_order_is_enforced(): void
    {
        $product = $this->productWithStock();
        $variant = $product->variants->first();
        $key = $product->id.'-'.$variant->id;

        Coupon::factory()->create([
            'code' => 'MINIMO500',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 500,
            'active' => true,
        ]);

        $this->withSession(['cart' => [$key => ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 1]]])
            ->post(route('cart.coupon.apply'), ['coupon' => 'MINIMO500'])
            ->assertSessionHasErrors('coupon');
    }
}
