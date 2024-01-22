<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use Carbon\Carbon;
class PerpanjanganSeeder extends Seeder
{
    public function run(): void
    {
        $max = 10;
        $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        if($masyarakatData->isEmpty()){
            $this->call(MasyarakatSeeder::class);
            $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        }
        $senimanData = Seniman::select('id_seniman')->where('status','diterima')->limit($max)->get();
        if($senimanData->isEmpty()){
            $this->call(SenimanActiveSeeder::class);
            $senimanData = Seniman::select('id_seniman')->where('status','diterima')->limit($max)->get();
        }
        foreach($masyarakatData as $user){
            Perpanjangan::insert([
                'nik'=>Crypt::encrypt(mt_rand(1000000000000000,9999999999999999)),
                'ktp_seniman'=>Str::random(30),
                'pass_foto'=>Str::random(30),
                'surat_keterangan'=>Str::random(30),
                'status'=>'diajukan',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'id_seniman'=>$senimanData[mt_rand(0,$senimanData->count() - 1)]->id_seniman,
                'id_user'=>$user->id_user,
            ]);
        }
    }
}