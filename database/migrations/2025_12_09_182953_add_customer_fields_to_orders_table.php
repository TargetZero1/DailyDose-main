<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
            $table->unsignedBigInteger('discount_id')->nullable()->after('discount');
            $table->foreign('discount_id')->references('id')->on('discounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Discount::class);
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_address', 'discount_id']);
        });
    }
};
