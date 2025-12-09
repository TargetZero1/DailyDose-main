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
        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default('in_stock'); // 'in_stock', 'low_stock', 'out_of_stock'
            $table->integer('view_count')->default(0);
            $table->integer('sale_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('view_count');
            $table->dropColumn('sale_count');
            $table->dropColumn('is_featured');
            $table->dropColumn('is_new');
        });
    }
};
