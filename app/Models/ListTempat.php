<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListTempat extends Model
{
    use HasFactory;
    protected $table = "list_tempat";
    protected $primaryKey = "id_tempat";
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_tempat','alamat_tempat','deskripsi_tempat','pengelola','contact_person','foto_tempat'
    ];
}