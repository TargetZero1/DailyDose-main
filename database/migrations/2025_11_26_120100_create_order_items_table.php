<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // product_id is optional and may reference a non-database-managed catalog; keep nullable without FK to avoid constraint errors
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
