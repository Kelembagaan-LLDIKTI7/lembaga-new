<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LembagaAkreditasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lembaga_akreditasis = [
            [
                'id' => Str::uuid(),
                'lembaga_nama' => 'Badan Akreditasi Nasional Perguruan Tinggi',
                'lembaga_nama_singkat' =>'BAN-PT',
                'lembaga_logo' => 'ban_pt.png',
                'lembaga_status' => 'Aktif'
            ],
            [
                'id' => Str::uuid(),
                'lembaga_nama' => 'Lembaga Akreditasi Mandiri Perguruan Tinggi Kesehatan',
                'lembaga_nama_singkat' =>'LAM-PTKES',
                'lembaga_logo' => 'lamkes.png',
                'lembaga_status' => 'Aktif'
            ],
            [
                'id' => Str::uuid(),
                'lembaga_nama' => 'Lembaga Akreditasi Mandiri Teknik',
                'lembaga_nama_singkat' =>'LAM-Teknik',
                'lembaga_logo' => 'lam_teknik.png',
                'lembaga_status' => 'Aktif'
            ],
            [
                'id' => Str::uuid(),
                'lembaga_nama' => 'Lembaga Akreditasi Mandiri Informatika dan Komputer',
                'lembaga_nama_singkat' =>'LAM-INFOKOM',
                'lembaga_logo' => 'lam_infokom.png',
                'lembaga_status' => 'Aktif'
            ]
        ];
        DB::table('lembaga_akreditasis')->insert($lembaga_akreditasis);
    }
}
