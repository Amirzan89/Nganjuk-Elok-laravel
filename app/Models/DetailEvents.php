<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEvents extends Model
{
    use HasFactory;
    protected $table = "detail_events";
    protected $primaryKey = "id_detail";
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_event','deskripsi','tempat_event', 'tanggal_awal','tanggal_akhir','link_pendaftaran','poster_event','id_event'
    ];
}