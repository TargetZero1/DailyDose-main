<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user for orders (create one if needed)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'phone' => '08812345678',
                'role' => 'user',
            ]);
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please run ProductSeeder first.');
            return;
        }

        $orders = [
            [
                'user_id' => $user->id,
                'customer_name' => 'Rahul Gupta',
                'customer_phone' => '08912345678',
                'status' => 'completed',
                'payment_status' => 'paid',
                'total' => 0,
                'created_at' => Carbon::now()->subDays(5),
                'items' => [
                    ['product_id' => $products->first()->id, 'quantity' => 2],
                    ['product_id' => $products->get(1)->id ?? $products->first()->id, 'quantity' => 1],
                ],
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Preeti Sharma',
                'customer_phone' => '08923456789',
                'status' => 'pending',
                'payment_status' => 'pending',
                'total' => 0,
                'created_at' => Carbon::now()->subDays(3),
                'items' => [
                    ['product_id' => $products->get(2)->id ?? $products->first()->id, 'quantity' => 3],
                ],
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Amit Patel',
                'customer_phone' => '08934567890',
                'status' => 'completed',
                'payment_status' => 'paid',
                'total' => 0,
                'created_at' => Carbon::now()->subDays(2),
                'items' => [
                    ['product_id' => $products->get(3)->id ?? $products->first()->id, 'quantity' => 1],
                    ['product_id' => $products->get(4)->id ?? $products->first()->id, 'quantity' => 2],
                    ['product_id' => $products->get(5)->id ?? $products->first()->id, 'quantity' => 1],
                ],
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Divya Nair',
                'customer_phone' => '08945678901',
                'status' => 'processing',
                'payment_status' => 'paid',
                'total' => 0,
                'created_at' => Carbon::now()->subDays(1),
                'items' => [
                    ['product_id' => $products->first()->id, 'quantity' => 4],
                ],
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Sanjay Rao',
                'customer_phone' => '08956789012',
                'status' => 'completed',
                'payment_status' => 'paid',
                'total' => 0,
                'created_at' => Carbon::now(),
                'items' => [
                    ['product_id' => $products->get(6)->id ?? $products->first()->id, 'quantity' => 2],
                    ['product_id' => $products->get(7)->id ?? $products->first()->id, 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            $createdAt = $orderData['created_at'] ?? Carbon::now();
            unset($orderData['items']);

            // Calculate total amount
            $totalAmount = 0;
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $totalAmount += $product->price * $item['quantity'];
                }
            }

            $orderData['total'] = $totalAmount;
            $order = Order::create($orderData);

            // Create order items
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $product->price * $item['quantity'],
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }

        $this->command->info('Orders seeded successfully!');
    }
}
