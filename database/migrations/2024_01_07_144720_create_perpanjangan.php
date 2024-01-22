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
            $table->string('nik',500);
            $table->text('ktp_seniman');
            $table->text('pass_foto');
            $table->text('surat_keterangan');
            $table->string('kode_verifikasi',45)->nullable();
            $table->enum('status',['diajukan','proses','diterima','ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('id_seniman');
            $table->foreign('id_seniman')->references('id_seniman')->on('seniman')->onDelete('cascade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan');
    }
};