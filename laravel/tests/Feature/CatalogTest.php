<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_shows_hero_and_featured_products(): void
    {
        $featured = Product::factory()->create(['featured' => true, 'active' => true]);
        $hidden = Product::factory()->create(['active' => false]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('SUPERA')
            ->assertSee($featured->name)
            ->assertDontSee($hidden->name);
    }

    public function test_products_index_lists_active_products_with_pagination(): void
    {
        Category::factory()->create(['slug' => 'ropa-deportiva', 'name' => 'Ropa Deportiva']);

        $product = Product::factory()->create(['active' => true, 'name' => 'Camiseta Dry-Fit Pro']);
        Product::factory()->create(['active' => false, 'name' => 'Producto Inactivo']);

        $response = $this->get(route('products.index'));

        $response->assertOk()
            ->assertSee('Camiseta Dry-Fit Pro')
            ->assertDontSee('Producto Inactivo');
    }

    public function test_products_index_filters_by_category_and_sale(): void
    {
        $ropa = Category::factory()->create(['slug' => 'ropa-deportiva']);
        $zapatillas = Category::factory()->create(['slug' => 'zapatillas']);

        Product::factory()->create(['category_id' => $ropa->id, 'active' => true, 'name' => 'Short Running Elite']);
        Product::factory()->create([
            'category_id' => $zapatillas->id,
            'active' => true,
            'name' => 'Zapatillas Cloud Run',
            'on_sale' => true,
        ]);

        $this->get(route('products.index', ['categoria' => 'ropa-deportiva']))
            ->assertOk()
            ->assertSee('Short Running Elite')
            ->assertDontSee('Zapatillas Cloud Run');

        $this->get(route('products.index', ['onSale' => 1]))
            ->assertOk()
            ->assertSee('Zapatillas Cloud Run')
            ->assertDontSee('Short Running Elite');
    }

    public function test_products_index_searches_by_name(): void
    {
        Product::factory()->create(['active' => true, 'name' => 'Mochila Deportiva 30L']);
        Product::factory()->create(['active' => true, 'name' => 'Gorra Running Cap']);

        $this->get(route('products.index', ['q' => 'mochila']))
            ->assertOk()
            ->assertSee('Mochila Deportiva 30L')
            ->assertDontSee('Gorra Running Cap');
    }

    public function test_product_show_page_displays_details_and_variants(): void
    {
        $product = Product::factory()->create(['active' => true, 'description' => 'Camiseta técnica']);
        $product->variants()->create(['size' => 'M', 'color' => 'Negro', 'stock' => 5, 'active' => true]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk()
            ->assertSee($product->name)
            ->assertSee('Camiseta técnica')
            ->assertSee('Negro')
            ->assertSee('Agregar al carrito');
    }

    public function test_inactive_products_are_not_published(): void
    {
        $product = Product::factory()->create(['active' => false]);

        $this->get(route('products.show', $product))->assertNotFound();
    }
}
