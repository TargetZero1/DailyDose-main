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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // 'percentage', 'fixed', 'buy_one_get_one'
            $table->unsignedBigInteger('value'); // Discount value or fixed amount
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('min_quantity')->default(1);
            $table->string('banner_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
