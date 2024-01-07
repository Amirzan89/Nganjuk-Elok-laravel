<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_lengkap',50);
            $table->string('no_telpon',15);
            $table->enum('jenis_kelamin',['laki-laki','perempuan']);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir',45);
            $table->enum('role',['super admin', 'admin event', 'admin seniman', 'admin tempat', 'masyarakat']);
            $table->string('email',45);
            $table->string('password');
            $table->string('foto',50);
            $table->boolean('verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};