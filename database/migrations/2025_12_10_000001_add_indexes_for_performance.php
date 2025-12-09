<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add indexes for better query performance
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
            $table->index('payment_status', 'idx_orders_payment_status');
            $table->index(['created_at', 'status'], 'idx_orders_created_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id', 'idx_order_items_product');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('stock', 'idx_products_stock');
            $table->index('category', 'idx_products_category');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_payment_status');
            $table->dropIndex('idx_orders_created_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_product');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_stock');
            $table->dropIndex('idx_products_category');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
        });
    }
};
