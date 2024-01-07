<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_tempat', function (Blueprint $table) {
            $table->id('id_tempat');
            $table->string('nama_tempat',50);
            $table->string('alamat_tempat',50);
            $table->string('deskripsi_tempat',500);
            $table->string('pengelola',50);
            $table->string('contact_person',15);
            $table->string('foto_tempat',45);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_tempat');
    }
};
