<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seniman extends Model
{
    use HasFactory;
    protected $table = "seniman";
    protected $primaryKey = "seniman";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nik','nomor_induk','nama_seniman','jenis_kelamin','kecamatan','tempat_lahir','tanggal_lahir','alamat_seniman','no_telpon','nama_organisasi','jumlah_anggota','ktp_seniman','pass_foto','surat_keterangan','tgl_pembuatan','tgl_berlaku','kode_verifikasi','status','catatan','id_kategori_seniman','id_user'
    ];
}