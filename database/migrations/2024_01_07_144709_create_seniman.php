<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seniman', function (Blueprint $table) {
            $table->id('id_seniman');
            $table->string('nik',50);
            $table->string('nomor_induk',30)->nullable();
            $table->string('nama_seniman',30);
            $table->enum('jenis_kelamin',['laki-laki','perempuan']);
            $table->enum('kecamatan',['bagor', 'baron', 'berbek', 'gondang', 'jatikalen', 'kertosono', 'lengkong', 'loceret', 'nganjuk', 'ngetos', 'ngluyu', 'ngronggot', 'pace', 'patianrowo', 'prambon', 'rejoso', 'sawahan', 'sukomoro', 'tanjunganom', 'wilangan']);
            $table->string('tempat_lahir',30);
            $table->date('tanggal_lahir');
            $table->string('alamat_seniman',50);
            $table->string('no_telpon',15);
            $table->string('nama_organisasi',50)->nullable();
            $table->integer('jumlah_anggota',5)->nullable();
            $table->text('ktp_seniman');
            $table->text('pass_foto');
            $table->text('surat_keterangan');
            $table->date('tgl_pembuatan');
            $table->date('tgl_berlaku');
            $table->string('kode_verifikasi',45)->nullable();
            $table->enum('status',['diajukan','proses','diterima','ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seniman');
    }
};