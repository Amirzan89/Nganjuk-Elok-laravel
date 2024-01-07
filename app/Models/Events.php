<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;
    protected $table = "events";
    protected $primaryKey = "id_event";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nama_pengirim','status','catatan', 'id_detail','id_user'
    ];
}