<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BentukPtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listBentuk = [
            'Akademi',
            'Akademi Komunitas',
            'Politeknik',
            'Sekolah Tinggi',
            'Institut',
            'Universitas',
        ];

        foreach ($listBentuk as $bentuk) {
            DB::table('bentuk_pts')->insert([
                'id' => Str::uuid(),
                'bentuk_nama' => $bentuk,
                'created_at' => now(),
            ]);
        }
    }
}
