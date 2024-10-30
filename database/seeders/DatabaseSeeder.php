<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JabatanSeeder::class);
        $this->call(JenisSuratKeputusanSeeder::class);
        $this->call(KotaSeeder::class);
        $this->call(LembagaAkreditasiSeeder::class);
        $this->call(PeringkatAkreditasiSeeder::class);
        $this->call(OrganisasiTypeSeeder::class);
        $this->call(OrganisasiSeeder::class);
        $this->call(UserSeeder::class);
    }
}
