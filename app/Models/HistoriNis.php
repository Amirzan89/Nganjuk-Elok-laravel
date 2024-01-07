<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriNis extends Model
{
    use HasFactory;
    protected $table = "histori_nis";
    protected $primaryKey = "id_histori";
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nis','tahun','id_seniman'
    ];
}