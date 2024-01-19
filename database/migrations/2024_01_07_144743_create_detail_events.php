<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_events', function (Blueprint $table) {
            $table->id('id_detail');
            $table->string('nama_event',45);
            $table->string('deskripsi',4000);
            $table->string('tempat_event',2000);
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('link_pendaftaran',2000)->nullable();
            $table->text('poster_event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_events');
    }
};