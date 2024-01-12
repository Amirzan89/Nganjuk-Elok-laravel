<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'nama_lengkap'=>'Super Admin',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'laki-laki',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Jakarta indonesia planet bumi',
                'role'=>'super admin',
                'email'=>'SuperAdmin@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>'',
                'verifikasi'=>true
            ],
            [
                'nama_lengkap'=>'Admin tester',
                'no_telpon'=>'0851'.mt_rand(10000000,99999999),
                'jenis_kelamin'=>'perempuan',
                'tanggal_lahir'=>Carbon::now(),
                'tempat_lahir'=>'Surabaya indonesia planet bumi',
                'role'=>'super admin',
                'email'=>'adminTester@gmail.com',
                'password'=>Hash::make('Admin@1234567890'),
                'foto'=>'',
                'verifikasi'=>true
            ]
        ]);
    }
}