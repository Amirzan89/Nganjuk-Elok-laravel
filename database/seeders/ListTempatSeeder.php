<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ListTempat;
use Illuminate\Support\Facades\File;
class ListTempatSeeder extends Seeder
{
    private function tempatData(): array
    {
        return [
            [
                'nama_tempat'=>'Museum Anjuk Ladang',
                'alamat_tempat'=>'Jl. Gatot Subroto Kec. Nganjuk Kab. Nganjuk',
                'deskripsi_tempat'=>'Museum Anjuk Ladang Terletak di kota Nganjuk, tepatnya sebelah timur Terminal Bus Kota Nganjuk, di dalamnya tersimpan benda dan cagar budaya pada zaman Hindu, Doho dan Majapahit yang terdapat di daerah Kabupaten Nganjuk. Disamping itu di simpan Prasasti Anjuk Ladang yang merupakan cikal bakal berdirinya Kabupaten Nganjuk.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111111',
                'foto_tempat'=>'/1.png',
            ],
            [
                'nama_tempat'=>'Balai Budaya',
                'alamat_tempat'=>'Mangundikaran, Kec. Nganjuk, Kab. Nganjuk',
                'deskripsi_tempat'=>'Gedung Balai Budaya Nganjuk adalah salah satu legenda bangunan bersejarah di Kabupaten Nganjuk. Gedung ini bisa digunakan untuk berbagai acara.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111112',
                'foto_tempat'=>'/2.png',
            ],
            [
                'nama_tempat'=>'Monumen Dr. Soetomo',
                'alamat_tempat'=>'Sono, Ngepeh, Kec. Loceret Kab. Nganjuk',
                'deskripsi_tempat'=>'Monumen Dr. Soetomo Nganjuk yang menempati tanah seluas 3,5 ha ini merupakan tempat kelahiran Dr. Soetomo Secara keseluruhan kompleks bangunan ini terdiri dari patung Dr. Soetomo, Pendopo induk, yang terletak di belakang patung, dan bangunan pringgitan jumlahnya 2 buah masing-masing 6 x 12 m.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111113',
                'foto_tempat'=>'/3.png',
            ],
            [
                'nama_tempat'=>'Air Terjun Sedudo',
                'alamat_tempat'=>'Jl. Sedudo Kec. Sawahan Kab. Nganjuk',
                'deskripsi_tempat'=>'Air Terjun Sedudo adalah sebuah air terjun dan objek wisata yang terletak di Desa Ngliman Kecamatan Sawahan, Kabupaten Nganjuk, Jawa Timur. Jaraknya sekitar 30 km arah selatan ibu kota kabupaten Nganjuk. Berada pada ketinggian 1.438 meter dpl, ketinggian air terjun ini sekitar 105 meter. Tempat wisata ini memiliki fasilitas yang cukup baik, dan jalur transportasi yang mudah diakses.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111114',
                'foto_tempat'=>'/4.png',
            ],
            [
                'nama_tempat'=>'Goa Margo Tresno ',
                'alamat_tempat'=>'Ngluyu, Kec. Ngluyu Kab. Nganjuk ',
                'deskripsi_tempat'=>'Goa Margo Tresno adalah salah satu obyek wisata di Jawa Timur yang terletak di Dusun Cabean, Desa Sugih Waras, Kecamatan Ngluyu, Kabupaten Nganjuk. Wisata Goa Margo Tresno Nganjuk adalah destinasi wisata yang ramai dengan wisatawan baik dari dalam maupun luar kota pada hari biasa maupun hari liburan dan sudah terkenal di Nganjuk dan sekitarnya.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111115',
                'foto_tempat'=>'/5.png',
            ],
            [
                'nama_tempat'=>'Air Terjun Roro Kuning',
                'alamat_tempat'=>'Nglarangan, Bajulan, Kec. Loceret Kab. Nganjuk',
                'deskripsi_tempat'=>'Air Terjun Roro Kuning adalah sebuah air terjun yang berada sekitar 27-30 km selatan kota Nganjuk, di ketinggian 600 m dpl dan memiliki tinggi antara 10-15 m. Air terjun ini mengalir dari tiga sumber di sekitar Gunung Wilis yang mengalir merambat di sela-sela bebatuan padas di bawah pepohonan hutan pinus.',
                'pengelola'=>'random',
                'contact_person'=>'0888111111116',
                'foto_tempat'=>'/6.png',
            ],
        ];
    }
    public function run(): void
    {
        if (app()->environment('local')) {
            $destinationPath = public_path('img/tempat');
        } else {
            $destinationPath = base_path('../public_html/public/img/tempat/');
        }
        if (File::isDirectory($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }
        mkdir(public_path('img/tempat'));
        foreach($this->tempatData() as $tempat){
            ListTempat::insert($tempat);
            $fotoPublic = public_path('img/tempat'.$tempat['foto_tempat']);
            if (!file_exists($fotoPublic)) {
                copy(database_path('seeders/FotoTempat'.$tempat['foto_tempat']), $fotoPublic);
            }
        }
    }
}