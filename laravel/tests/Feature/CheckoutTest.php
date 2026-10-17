<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * @return array{0: Product, 1: string}
     */
    private function cartWithOneProduct(int $price = 100, int $stock = 10, int $quantity = 1, ?string $coupon = null): array
    {
        $product = Product::factory()->create(['active' => true, 'price' => $price]);
        $product->variants()->create(['size' => 'M', 'color' => 'Negro', 'stock' => $stock, 'active' => true]);

        $variant = $product->variants->first();
        $key = $product->id.'-'.$variant->id;

        $session = [
            'cart' => [$key => ['product_id' => $product->id, 'variant_id' => $variant->id, 'quantity' => $quantity]],
        ];

        if ($coupon !== null) {
            $session['coupon'] = $coupon;
        }

        $this->withSession($session);

        return [$product->load('variants'), $key];
    }

    private function validCheckoutData(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'María Pérez',
            'customer_phone' => '999888777',
            'department' => 'Lima',
            'province' => 'Lima',
            'district' => 'Miraflores',
            'address' => 'Av. La Marina 1234',
            'shipping_method' => 'lima',
            'payment_method' => 'yape',
        ], $overrides);
    }

    public function test_guest_with_empty_cart_is_redirected_to_cart(): void
    {
        $this->get(route('checkout.create'))->assertRedirect(route('cart.index'));
    }

    public function test_checkout_form_is_shown_with_cart_contents(): void
    {
        $this->cartWithOneProduct();

        $this->get(route('checkout.create'))
            ->assertOk()
            ->assertSee('Finalizar compra')
            ->assertSee('Dirección de entrega');
    }

    public function test_checkout_validates_required_customer_data(): void
    {
        $this->cartWithOneProduct();

        $this->post(route('checkout.store'), [])
            ->assertSessionHasErrors(['customer_name', 'customer_phone', 'department', 'shipping_method', 'payment_method']);

        $this->assertSame(0, Order::query()->count());
    }

    public function test_checkout_creates_order_and_decrements_stock(): void
    {
        [$product] = $this->cartWithOneProduct(price: 100, stock: 10, quantity: 1);

        $response = $this->post(route('checkout.store'), $this->validCheckoutData());

        $order = Order::query()->firstOrFail();

        $response->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(100.0, (float) $order->subtotal);
        $this->assertSame(10.0, (float) $order->shipping_cost);
        $this->assertSame(110.0, (float) $order->total);
        $this->assertSame(OrderStatus::Pending, $order->order_status);
        $this->assertSame(PaymentStatus::Pending, $order->payment_status);
        $this->assertStringStartsWith('FS-', $order->code);

        $this->assertSame(9, $product->variants->first()->fresh()->stock);
        $this->assertSame(1, $order->items->first()->quantity);

        $response->assertSessionMissing('cart');
    }

    public function test_checkout_applies_coupon_discount_and_free_shipping_threshold(): void
    {
        Coupon::factory()->create([
            'code' => 'AHORRA20',
            'type' => 'fixed',
            'value' => 20,
            'min_order' => 200,
            'active' => true,
        ]);

        [$product] = $this->cartWithOneProduct(price: 250, stock: 5, quantity: 1, coupon: 'AHORRA20');

        $response = $this->post(route('checkout.store'), $this->validCheckoutData());

        $order = Order::query()->firstOrFail();

        // Subtotal 250 >= 200 => envío gratis; descuento fijo de S/20.
        $this->assertSame(250.0, (float) $order->subtotal);
        $this->assertSame(20.0, (float) $order->discount);
        $this->assertSame(0.0, (float) $order->shipping_cost);
        $this->assertSame(230.0, (float) $order->total);
        $this->assertSame('AHORRA20', $order->coupon_code);
        $this->assertSame(1, Coupon::query()->firstOrFail()->used_count);
        $this->assertSame(4, $product->variants->first()->fresh()->stock);
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $this->cartWithOneProduct(price: 100, stock: 5, quantity: 5);

        // Otro usuario consume todo el stock justo antes de confirmar.
        $product = Product::query()->firstOrFail();
        $product->variants->first()->update(['stock' => 0]);

        $this->post(route('checkout.store'), $this->validCheckoutData())
            ->assertSessionHasErrors('cart');

        $this->assertSame(0, Order::query()->count());
    }

    public function test_order_confirmation_page_is_public_by_code(): void
    {
        $this->cartWithOneProduct();

        $this->post(route('checkout.store'), $this->validCheckoutData());

        $order = Order::query()->firstOrFail();

        $this->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($order->code)
            ->assertSee('¡Gracias por tu pedido!');
    }
}
