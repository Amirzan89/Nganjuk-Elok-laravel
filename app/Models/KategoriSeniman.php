<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSeniman extends Model
{
    use HasFactory;
    protected $table = "kategori_seniman";
    protected $primaryKey = "id_kategori_seniman";
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_kategori','singkatan_kategori'
    ];
}