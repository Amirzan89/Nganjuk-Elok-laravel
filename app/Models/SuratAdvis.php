<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratAdvis extends Model
{
    use HasFactory;
    protected $table = "surat_advis";
    protected $primaryKey = "id_advis";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nomor_induk','nama_advis','alamat_advis','deskripsi_advis','tgl_advis','tempat_advis','kode_verifikasi','status','catatan','id_user','id_seniman'
    ];
}
