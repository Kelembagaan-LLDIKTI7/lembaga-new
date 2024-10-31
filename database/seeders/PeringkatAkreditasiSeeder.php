<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeringkatAkreditasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('peringkat_akreditasis')->insert([
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'A',
                'peringkat_logo' => 'A.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'B',
                'peringkat_logo' => 'B.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'Baik',
                'peringkat_logo' => 'baik.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'Baik Sekali',
                'peringkat_logo' => 'baik_sekali.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'Cukup',
                'peringkat_logo' => 'C.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'Unggul',
                'peringkat_logo' => 'unggul.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'belum Terakreditasi/Kedaluarsa',
                'peringkat_logo' => 'belum Terakreditasi/Kedaluarsa.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'peringkat_nama' => 'Tidak Terakreditasi',
                'peringkat_logo' => 'Tidak Terakreditasi.png',
                'peringkat_status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
