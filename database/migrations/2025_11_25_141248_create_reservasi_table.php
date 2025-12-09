<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp');
            $table->date('tanggal');
            $table->time('waktu');
            $table->integer('jumlah');
            $table->string('area');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservasi');
    }
};
