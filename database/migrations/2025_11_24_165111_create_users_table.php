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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // username untuk login (ADMIN, OWN, atau bebas)
            $table->string('username')->unique();

            // password
            $table->string('password');

            // nomor hp pelanggan/admin/pemilik
            $table->string('no_hp');

            // role otomatis berdasarkan prefix username
            $table->enum('role', ['admin', 'pemilik', 'pelanggan'])
                  ->default('pelanggan');

            // token login (wajib bawaan Laravel)
            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
