<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SuperAdminSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(MasyarakatSeeder::class);
        $this->call(ListTempatSeeder::class);
        $this->call(SewaTempatSeeder::class);
        $this->call(KategoriSenimanSeeder::class);
        $this->call(SenimanSeeder::class);
        $this->call(SenimanActiveSeeder::class);
        $this->call(PerpanjanganSeeder::class);
        $this->call(SuratAdvisSeeder::class);
        $this->call(EventSeeder::class);
    }
}