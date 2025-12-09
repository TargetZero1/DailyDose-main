<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Discount code (e.g., "WELCOME10")
            $table->string('name'); // Display name
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // percentage or fixed amount
            $table->decimal('value', 10, 2); // 10 for 10% or 50000 for Rp 50,000
            $table->decimal('min_purchase', 10, 2)->default(0); // Minimum purchase amount
            $table->decimal('max_discount', 10, 2)->nullable(); // Maximum discount amount (for percentage)
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_count')->default(0); // Current usage count
            $table->integer('per_user_limit')->nullable(); // Usage limit per user
            $table->boolean('is_active')->default(true);
            $table->enum('applicable_to', ['products', 'reservations', 'both'])->default('both');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });

        // Junction table for tracking user discount usage
        Schema::create('discount_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->integer('usage_count')->default(1);
            $table->timestamps();
            
            $table->unique(['user_id', 'discount_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_usage');
        Schema::dropIfExists('discounts');
    }
};
