<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_nis', function (Blueprint $table) {
            $table->id('id_histori');
            $table->string('nis',45);
            $table->string('tahun',5);
            $table->unsignedBigInteger('id_seniman');
            $table->foreign('id_seniman')->references('id_seniman')->on('seniman')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_nis');
    }
};