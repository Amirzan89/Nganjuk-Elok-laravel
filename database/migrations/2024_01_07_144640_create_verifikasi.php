<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id('id_verifikasi');
            $table->string('email',45);
            $table->string('kode_otp',6);
            $table->string('link');
            $table->enum('deskripsi',['password','email']);
            $table->integer('send',2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi');
    }
};