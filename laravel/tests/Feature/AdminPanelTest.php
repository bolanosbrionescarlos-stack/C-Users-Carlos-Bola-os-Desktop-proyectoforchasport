<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_guest_is_redirected_to_login_from_admin_panel(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = $this->admin();

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_customer_cannot_login_to_admin_panel(): void
    {
        $customer = User::factory()->create();

        $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_user_gets_forbidden_on_admin_routes(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_dashboard_shows_stats(): void
    {
        Product::factory()->count(3)->create(['active' => true]);
        Order::factory()->count(2)->create();

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Productos activos');
    }

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin())->post(route('admin.categorias.store'), [
            'name' => 'Natación',
            'slug' => 'natacion',
            'description' => 'Trajes, gafas y accesorios',
            'icon' => '🏊',
            'color' => '#0055AA',
            'active' => 1,
            'sort_order' => 6,
        ])->assertRedirect(route('admin.categorias.index'));

        $this->assertDatabaseHas('categories', ['slug' => 'natacion', 'active' => true]);
    }

    public function test_admin_can_create_product_with_variants(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())->post(route('admin.productos.store'), [
            'name' => 'Traje de Natación Pro',
            'slug' => 'traje-natacion-pro',
            'description' => 'Traje de competición',
            'price' => 129.90,
            'category_id' => $category->id,
            'active' => 1,
            'variants' => [
                ['size' => 'M', 'color' => 'Negro', 'stock' => 10],
                ['size' => 'L', 'color' => 'Azul', 'stock' => 5],
                ['size' => '', 'color' => '', 'stock' => 0],
            ],
        ])->assertRedirect(route('admin.productos.index'));

        $product = Product::query()->where('slug', 'traje-natacion-pro')->firstOrFail();

        $this->assertSame(2, $product->variants()->count());
    }

    public function test_admin_can_update_order_status(): void
    {
        $order = Order::factory()->create(['order_status' => OrderStatus::Pending]);

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.status', $order), ['order_status' => OrderStatus::Shipped->value])
            ->assertRedirect();

        $this->assertSame(OrderStatus::Shipped, $order->fresh()->order_status);
    }

    public function test_admin_can_create_and_manage_coupons(): void
    {
        $this->actingAs($this->admin())->post(route('admin.cupones.store'), [
            'code' => 'navidad2026',
            'type' => 'percent',
            'value' => 25,
            'min_order' => 100,
            'active' => 1,
        ])->assertRedirect(route('admin.cupones.index'));

        $this->assertDatabaseHas('coupons', ['code' => 'NAVIDAD2026', 'value' => 25]);
    }

    public function test_admin_can_delete_category_without_products(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.categorias.destroy', $category))
            ->assertRedirect(route('admin.categorias.index'));

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($this->admin())
            ->from(route('admin.categorias.index'))
            ->delete(route('admin.categorias.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertNotSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_coupon_model_computes_discount_amounts(): void
    {
        $percent = new Coupon(['code' => 'X', 'type' => 'percent', 'value' => 10]);
        $fixed = new Coupon(['code' => 'Y', 'type' => 'fixed', 'value' => 25]);

        $this->assertSame(10.0, $percent->discountFor(100));
        $this->assertSame(25.0, $fixed->discountFor(100));
        // El descuento fijo nunca supera el subtotal.
        $this->assertSame(25.0, $fixed->discountFor(30));
    }
}
