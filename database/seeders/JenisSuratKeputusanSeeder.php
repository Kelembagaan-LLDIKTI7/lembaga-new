<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JenisSuratKeputusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_surat_keputusans')->insert([
            'id' => Str::uuid()->toString(),
            'jsk_nama' => 'SK Pendirian PT',
        ]);

        DB::table('jenis_surat_keputusans')->insert([
            'id' => Str::uuid()->toString(),
            'jsk_nama' => 'SK Penutupan PT',
        ]);

        DB::table('jenis_surat_keputusans')->insert([
            'id' => Str::uuid()->toString(),
            'jsk_nama' => 'SK Alih Bentuk PT',
        ]);

        DB::table('jenis_surat_keputusans')->insert([
            'id' => Str::uuid()->toString(),
            'jsk_nama' => 'SK Alih Kelola PT',
        ]);

        DB::table('jenis_surat_keputusans')->insert([
            'id' => Str::uuid()->toString(),
            'jsk_nama' => 'SK Perubahan/Penetapan BP',
        ]);
    }
}
