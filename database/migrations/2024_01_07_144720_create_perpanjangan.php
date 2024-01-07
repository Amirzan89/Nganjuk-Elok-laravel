<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpanjangan', function (Blueprint $table) {
            $table->id('id_perpanjangan');
            $table->string('nik',50);
            $table->text('ktp_seniman');
            $table->text('pass_foto');
            $table->text('surat_keterangan');
            $table->date('tgl_pembuatan');
            $table->string('kode_verifikasi',45)->nullable();
            $table->enum('status',['diajukan','proses','diterima','ditolak']);
            $table->text('catatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan');
    }
};