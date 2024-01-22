<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Models\KategoriSeniman;
use App\Models\Seniman;
use App\Models\User;
use App\Models\SuratAdvis;
use Carbon\Carbon;
class SuratAdvisSeeder extends Seeder
{
    public function run(): void
    {
        $max = 10;
        $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        if($masyarakatData->isEmpty()){
            $this->call(MasyarakatSeeder::class);
            $masyarakatData = User::select('id_user')->where('role','masyarakat')->limit($max)->get();
        }
        //check kategori
        $kategoriData = KategoriSeniman::select('id_kategori_seniman','singkatan_kategori')->get();
        if($kategoriData->isEmpty()){
            $this->call(KategoriSenimanSeeder::class);
            $kategoriData = KategoriSeniman::select('id_kategori_seniman','singkatan_kategori')->get();
        }
        $senimanData = Seniman::select('id_seniman', 'nomor_induk')->where('status', 'diterima')->limit($max)->get();
        if($senimanData->isEmpty()){
            $this->call(SenimanActiveSeeder::class);
            $senimanData = Seniman::select('id_seniman', 'nomor_induk')->where('status','diterima')->limit($max)->get();
        }
        for($i = 0; $i < $max; $i++){
            SuratAdvis::insert([
                'nomor_induk' => $senimanData[$i]['nomor_induk'],
                'nama_advis' => Str::random(10),
                'alamat_advis' => Str::random(50),
                'deskripsi_advis' => Str::random(50),
                'tgl_advis' => Carbon::now(),
                'tempat_advis' => Str::random(20),
                'status' => 'diajukan',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'id_seniman' => $senimanData[$i]['id_seniman'],
                'id_user'=>$masyarakatData[$i]['id_user'],
            ]);
        }
    }
}