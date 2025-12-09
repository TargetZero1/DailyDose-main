<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an order successfully.
     */
    public function test_creates_order_successfully(): void
    {
        $user = User::factory()->create();

        $orderData = [
            'cart' => json_encode([
                [
                    'name' => 'Chocolate Cake',
                    'price' => 75000,
                    'quantity' => 2,
                    'product_id' => 1,
                ]
            ]),
            'notes' => 'Please deliver before 5 PM',
            'customer_name' => 'Test Customer',
        ];

        $response = $this->actingAs($user)->post(route('orders.store'), $orderData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test viewing orders list.
     */
    public function test_user_can_view_their_orders(): void
    {
        $user = User::factory()->create();
        
        Order::factory()->create([
            'user_id' => $user->id,
            'total' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertSee('My Orders');
    }

    /**
     * Test viewing single order details.
     */
    public function test_user_can_view_order_details(): void
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total' => 150000,
        ]);

        $response = $this->actingAs($user)->get(route('orders.show', $order->id));

        $response->assertStatus(200);
    }

    /**
     * Test user cannot view other user's orders.
     */
    public function test_user_cannot_view_others_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->get(route('orders.show', $order->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Test reordering functionality.
     */
    public function test_user_can_reorder_previous_order(): void
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'total' => 100000,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'name' => 'Ice Cream',
            'quantity' => 1,
            'price' => 50000,
            'subtotal' => 50000,
        ]);

        $response = $this->actingAs($user)->post(route('orders.reorder', $order->id));

        $response->assertStatus(302);
        $this->assertEquals(2, Order::where('user_id', $user->id)->count());
    }
}
