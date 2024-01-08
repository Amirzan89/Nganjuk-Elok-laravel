<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sewa_tempat', function (Blueprint $table) {
            $table->id('id_sewa');
            $table->string('nik_sewa',50);
            $table->string('nama_peminjam',30);
            $table->string('nama_tempat',50);
            $table->string('deskripsi_sewa_tempat',100);
            $table->string('nama_kegiatan_sewa',50);
            $table->unsignedBigInteger('jumlah_peserta')->nullable();
            $table->string('instansi',50)->nullable();
            $table->text('surat_ket_sewa');
            $table->dateTime('tgl_awal_peminjaman');
            $table->dateTime('tgl_akhir_peminjaman');
            $table->string('kode_pinjam',45)->nullable();
            $table->enum('status',['diajukan','proses','diterima','ditolak']);
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sewa_tempat');
    }
};