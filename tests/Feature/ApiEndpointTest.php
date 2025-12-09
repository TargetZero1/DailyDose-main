<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test products API endpoint returns JSON.
     */
    public function test_products_api_returns_json(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->get(route('products.api'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'price', 'category']
        ]);
    }

    /**
     * Test cart sync endpoint.
     */
    public function test_cart_sync_endpoint_works(): void
    {
        $user = User::factory()->create();

        $cartData = [
            'items' => [
                ['product_id' => 1, 'quantity' => 2],
                ['product_id' => 2, 'quantity' => 1],
            ]
        ];

        $response = $this->actingAs($user)
                         ->postJson(route('cart.sync'), $cartData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /**
     * Test favorites check endpoint.
     */
    public function test_favorites_check_endpoint(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
                         ->get(route('favorites.check', $product->id));

        $response->assertStatus(200);
    }

    /**
     * Test customizations store endpoint.
     */
    public function test_customizations_store_endpoint(): void
    {
        $user = User::factory()->create();

        $customizationData = [
            'product_id' => 1,
            'options' => ['size' => 'Large', 'topping' => 'Chocolate'],
        ];

        $response = $this->actingAs($user)
                         ->postJson(route('customizations.store'), $customizationData);

        $response->assertStatus(200);
    }

    /**
     * Test product reviews endpoint.
     */
    public function test_product_reviews_endpoint_returns_reviews(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.reviews', $product->id));

        $response->assertStatus(200);
    }

    /**
     * Test API endpoints require authentication where needed.
     */
    public function test_protected_api_endpoints_require_auth(): void
    {
        $protectedEndpoints = [
            ['method' => 'post', 'route' => 'cart.sync', 'data' => ['items' => []]],
            ['method' => 'post', 'route' => 'customizations.store', 'data' => []],
        ];

        foreach ($protectedEndpoints as $endpoint) {
            $response = $this->postJson(route($endpoint['route']), $endpoint['data']);
            $response->assertStatus(401); // Unauthorized
        }
    }
}
