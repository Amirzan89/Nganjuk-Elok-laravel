<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSeniman;
class KategoriSenimanSeeder extends Seeder
{
    public function run(): void
    {
        KategoriSeniman::insert([
            [
                "id_kategori_seniman"=> 1,
                "nama_kategori"=> "campursari",
                "singkatan_kategori"=> "CAMP"
            ],
            [
                "id_kategori_seniman"=> 2,
                "nama_kategori"=> "dalang",
                "singkatan_kategori"=> "DLG"
            ],
            [
                "id_kategori_seniman"=> 3,
                "nama_kategori"=> "jaranan",
                "singkatan_kategori"=> "JKP"
            ],
            [
                "id_kategori_seniman"=> 4,
                "nama_kategori"=> "karawitan",
                "singkatan_kategori"=> "KRW"
            ],
            [
                "id_kategori_seniman"=> 5,
                "nama_kategori"=> "mc",
                "singkatan_kategori"=> "MC"
            ],
            [
                "id_kategori_seniman"=> 6,
                "nama_kategori"=> "ludruk",
                "singkatan_kategori"=> "LDR"
            ],
            [
                "id_kategori_seniman"=> 7,
                "nama_kategori"=> "organisasi kesenian musik",
                "singkatan_kategori"=> "OKM"
            ],
            [
                "id_kategori_seniman"=> 8,
                "nama_kategori"=> "organisasi",
                "singkatan_kategori"=> "ORG"
            ],
            [
                "id_kategori_seniman"=> 9,
                "nama_kategori"=> "pramugari tayup",
                "singkatan_kategori"=> "PRAM"
            ],
            [
                "id_kategori_seniman"=> 10,
                "nama_kategori"=> "sanggar",
                "singkatan_kategori"=> "SGR"
            ],
            [
                "id_kategori_seniman"=> 11,
                "nama_kategori"=> "sinden",
                "singkatan_kategori"=> "SIND"
            ],
            [
                "id_kategori_seniman"=> 12,
                "nama_kategori"=> "vocalis",
                "singkatan_kategori"=> "VOC"
            ],
            [
                "id_kategori_seniman"=> 13,
                "nama_kategori"=> "waranggono",
                "singkatan_kategori"=> "WAR"
            ],
            [
                "id_kategori_seniman"=> 14,
                "nama_kategori"=> "barongsai",
                "singkatan_kategori"=> "BAR"
            ],
            [
                "id_kategori_seniman"=> 15,
                "nama_kategori"=> "ketoprak",
                "singkatan_kategori"=> "KTP"
            ],
            [
                "id_kategori_seniman"=> 16,
                "nama_kategori"=> "pataji",
                "singkatan_kategori"=> "PTJ"
            ],
            [
                "id_kategori_seniman"=> 17,
                "nama_kategori"=> "reog",
                "singkatan_kategori"=> "REOG"
            ],
            [
                "id_kategori_seniman"=> 18,
                "nama_kategori"=> "taman hiburan rakyat",
                "singkatan_kategori"=> "THR"
            ],
            [
                "id_kategori_seniman"=> 19,
                "nama_kategori"=> "pelawak",
                "singkatan_kategori"=> "PLWK"
            ]
        ]);
    }
}