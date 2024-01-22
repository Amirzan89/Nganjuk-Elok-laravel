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
class SenimanActiveSeeder extends Seeder
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
        foreach($masyarakatData as $user){
            $index = mt_rand(0,$kategoriData->count() - 1);
            $total = Seniman::where('nomor_induk', 'LIKE', '%/' . date('Y'))->where('id_kategori_seniman', $kategoriData[$index]->id_kategori_seniman)->count();
            $total = $total == 0 ? 1 : $total++;
            $total = str_pad($total, 3, '0', STR_PAD_LEFT);
            Seniman::insert([
                'nik'=>Crypt::encrypt(mt_rand(1000000000000000,9999999999999999)),
                'nomor_induk'=>$kategoriData[$index]->singkatan_kategori.'/'.str_pad($total, 3, '0', STR_PAD_LEFT).'/411.302/'.date('Y'),
                'nama_seniman'=>Str::random(30),
                'jenis_kelamin'=>['laki-laki', 'perempuan'][rand(0, 1)],
                'tempat_lahir'=>Str::random(30),
                'tanggal_lahir'=>Carbon::now(),
                'alamat_seniman'=>Str::random(30),
                'no_telpon'=>'085'.mt_rand(100000000,999999999),
                'ktp_seniman'=>Str::random(30),
                'pass_foto'=>Str::random(30),
                'surat_keterangan'=>Str::random(30),
                'tgl_berlaku'=>Carbon::now()->endOfYear(),
                'status'=>'diterima',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'id_kategori_seniman'=>$kategoriData[$index]->id_kategori_seniman,
                'id_user'=>$user->id_user,
            ]);
        }
    }
}
