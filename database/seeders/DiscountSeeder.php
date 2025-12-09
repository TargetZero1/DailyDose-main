<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount 10%',
                'description' => 'Get 10% off on your first order',
                'type' => 'percentage',
                'value' => 10.00,
                'min_purchase' => 50000.00,
                'max_discount' => 20000.00,
                'usage_limit' => 100,
                'per_user_limit' => 1,
                'is_active' => true,
                'applicable_to' => 'products',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVE15',
                'name' => 'Save 15% on Orders',
                'description' => 'Save 15% on all products',
                'type' => 'percentage',
                'value' => 15.00,
                'min_purchase' => 100000.00,
                'max_discount' => 50000.00,
                'usage_limit' => 50,
                'per_user_limit' => 3,
                'is_active' => true,
                'applicable_to' => 'products',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MEGADEAL',
                'name' => 'Mega Deal 20%',
                'description' => 'Huge 20% discount for big orders',
                'type' => 'percentage',
                'value' => 20.00,
                'min_purchase' => 200000.00,
                'max_discount' => 100000.00,
                'usage_limit' => 30,
                'per_user_limit' => 2,
                'is_active' => true,
                'applicable_to' => 'products',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FLAT25K',
                'name' => 'Flat Rp 25,000 Off',
                'description' => 'Get instant Rp 25,000 discount',
                'type' => 'fixed',
                'value' => 25000.00,
                'min_purchase' => 75000.00,
                'max_discount' => null,
                'usage_limit' => 75,
                'per_user_limit' => 2,
                'is_active' => true,
                'applicable_to' => 'products',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RESERVE10',
                'name' => 'Reservation Discount 10%',
                'description' => '10% off on table reservations',
                'type' => 'percentage',
                'value' => 10.00,
                'min_purchase' => 0.00,
                'max_discount' => 30000.00,
                'usage_limit' => null,
                'per_user_limit' => 5,
                'is_active' => true,
                'applicable_to' => 'reservations',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ALLSAVE',
                'name' => 'Universal 5% Discount',
                'description' => '5% discount on everything',
                'type' => 'percentage',
                'value' => 5.00,
                'min_purchase' => 30000.00,
                'max_discount' => 15000.00,
                'usage_limit' => null,
                'per_user_limit' => null,
                'is_active' => true,
                'applicable_to' => 'both',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('discounts')->insert($discounts);
    }
}
