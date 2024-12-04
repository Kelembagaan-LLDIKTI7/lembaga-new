<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listStatus = [
            'Aktif',
            'Pembinaan',
            'Alih Bentuk',
            'Tutup',
        ];

        $id = 1; // Start ID from 1
        foreach ($listStatus as $status) {
            DB::table('prodi_statuses')->insert([
                'id' => $id,
                'prodi_status_nama' => $status,
                'created_at' => now(),
            ]);
            $id++;
        }
    }
}
