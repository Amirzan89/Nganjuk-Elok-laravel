<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Events;
use App\Models\DetailEvents;
class EventSeeder extends Seeder
{
    public function run(): void
    {
        $max = 10;
        $adminData = User::select('id_user')->where('role','super admin')->limit($max)->get();
        if($adminData->isEmpty()){
            $this->call(SuperAdminSeeder::class);
            $adminData = User::select('id_user')->where('role','super admin')->limit(2)->get();
        }
        $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        if($masyarakatData->isEmpty()){
            $this->call(MasyarakatSeeder::class);
            $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        }
        foreach($masyarakatData as $user){
            $date = Carbon::now()->addDays(mt_rand(1,9));
            $idDetail = DetailEvents::insert([
                'nama_event'=>Str::random(30),
                'deskripsi'=>Str::random(2000),
                'tempat_event'=>Str::random(1000),
                'tanggal_awal'=>$date,
                'tanggal_akhir' => $date->copy()->addDays(mt_rand(1,9)),
                'link_pendaftaran'=>'htttps://'.Str::random(10).'.com',
                'poster_event'=>Str::random(5),
            ]);
            Events::insert([
                'nama_pengirim'=>Str::random(10),
                'status'=>'diajukan',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'id_detail'=>$idDetail,
                'id_user'=>$user->id_user,
            ]);
        }
    }
}