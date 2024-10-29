<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Rektor',
                'jabatan_status' => 'Aktif',
                'jabatan_organisasi' => 'perguruan tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Rektor I',
                'jabatan_status' => 'Aktif',
                'jabatan_organisasi' => 'perguruan tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Rektor II',
                'jabatan_status' => 'Aktif',
                'jabatan_organisasi' => 'perguruan tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Rektor III',
                'jabatan_status' => 'Aktif',
                'jabatan_organisasi' => 'perguruan tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('jabatans')->insert($jabatans);
    }
}
