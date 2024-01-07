<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SewaTempat extends Model
{
    use HasFactory;
    protected $table = "sewa_tempat";
    protected $primaryKey = "id_sewa";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nik_sewa','nama_peminjam','nama_tempat','deskripsi_sewa_tempat','nama_kegiatan_sewa','jumlah_peserta','instansi','surat_ket_sewa','tgl_awal_peminjaman','tgl_akhir_peminjaman','kode_pinjam','status','catatan','id_tempat','id_user'
    ];
}