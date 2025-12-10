<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => bcrypt('password'),
                'no_hp' => '6281234567890',
                'role' => 'admin',
            ]
        );

        // Create test customer user
        User::firstOrCreate(
            ['username' => 'customer'],
            [
                'password' => bcrypt('password'),
                'no_hp' => '6289876543210',
                'role' => 'pelanggan',
            ]
        );
        
        // Seed products
        $this->call(ProductSeeder::class);
        
        // Seed reservations
        $this->call(ReservasiSeeder::class);
        
        // Seed orders
        $this->call(OrderSeeder::class);
        
        // Seed test discounts
        Discount::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'name' => 'Welcome Discount',
                'description' => '10% off on first order',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 50000,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'per_user_limit' => 1,
                'is_active' => true,
                'applicable_to' => 'both',
            ]
        );
        
        Discount::firstOrCreate(
            ['code' => 'SAVE5000'],
            [
                'name' => 'Save 5000',
                'description' => 'Rp 5.000 off',
                'type' => 'fixed',
                'value' => 5000,
                'min_purchase' => 25000,
                'usage_limit' => 200,
                'per_user_limit' => 2,
                'is_active' => true,
                'applicable_to' => 'both',
            ]
        );
    }
}
