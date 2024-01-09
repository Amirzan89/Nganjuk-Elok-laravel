<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'nama_lengkap'=>'admin event',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'laki-laki',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin event',
                'email'=>'adminEvent@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'admin event1',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'perempuan',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin event',
                'email'=>'adminEvent1@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'admin tempat',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'laki-laki',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin tempat',
                'email'=>'adminTempat@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'admin tempat1',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'perempuan',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin tempat',
                'email'=>'adminTempat1@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'admin seniman',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'perempuan',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin seniman',
                'email'=>'adminSeniman@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'admin seniman1',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'perempuan',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'admin seniman',
                'email'=>'adminSeniman1@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>Str::random(5),
                'verifikasi'=>true
            ],
        ]);
    }
}