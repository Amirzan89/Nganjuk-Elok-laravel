<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\SewaTempat;
use Carbon\Carbon;
class SewaTempatSeeder extends Seeder
{
    public function run(): void
    {
        $max = 10;
        $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        if($masyarakatData->isEmpty()){
            $this->call(MasyarakatSeeder::class);
            $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        }
        foreach($masyarakatData as $user){
            $date = Carbon::now()->addDays(mt_rand(1,9));
            SewaTempat::insert([
                'nik_sewa'=>base64_encode(mt_rand(1000000000000000,9999999999999999)),
                'nama_peminjam'=>Str::random(15),
                'nama_tempat'=>Str::random(15),
                'deskripsi_sewa_tempat'=>Str::random(50),
                'nama_kegiatan_sewa'=>Str::random(30),
                'surat_ket_sewa'=>Str::random(10),
                'tgl_awal_peminjaman'=>$date,
                'tgl_akhir_peminjaman' => $date->copy()->addDays(mt_rand(1,9)),
                'status'=>'diajukan',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'id_user'=>$user->id_user,
                'id_tempat'=>1,
            ]);
        }
    }
}