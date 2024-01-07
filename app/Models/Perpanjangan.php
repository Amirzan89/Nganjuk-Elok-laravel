<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpanjangan extends Model
{
    use HasFactory;
    protected $table = "perpanjangan";
    protected $primaryKey = "id_perpanjangan";
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nik','ktp_seniman','pass_foto','surat_keterangan','tgl_pembuatan','kode_verifikasi','status','catatan','id_seniman','id_user'
    ];
}