<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
class MasyarakatSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 1; $i <= 10; $i++){
            User::insert([
                'nama_lengkap'=>'masyarakat_'.$i,
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>['laki-laki', 'perempuan'][rand(0, 1)],
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Surabaya indonesia planet bumi',
                'role'=>'masyarakat',
                'email'=>"masyarakat".$i."@gmail.com",
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ]);
        }
    }
}