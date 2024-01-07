<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_seniman', function (Blueprint $table) {
            $table->id('id_kategori_seniman');
            $table->string('nama_kategori');
            $table->string('singkatan_kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_seniman');
    }
};