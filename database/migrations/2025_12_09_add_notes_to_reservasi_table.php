<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reservasi', function (Blueprint $table) {
            if (!Schema::hasColumn('reservasi', 'notes')) {
                $table->text('notes')->nullable()->after('area');
            }
        });
    }

    public function down()
    {
        Schema::table('reservasi', function (Blueprint $table) {
            if (Schema::hasColumn('reservasi', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
