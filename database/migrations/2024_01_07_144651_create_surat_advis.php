<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_advis', function (Blueprint $table) {
            $table->id('id_advis');
            $table->string('nomor_induk',20);
            $table->string('nama_advis',30);
            $table->string('alamat_advis',100);
            $table->string('deskripsi_advis',100);
            $table->date('tgl_advis');
            $table->string('tempat_advis',30);
            $table->string('kode_verifikasi',45)->nullable();
            $table->enum('status',['diajukan','proses','diterima','ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_advis');
    }
};