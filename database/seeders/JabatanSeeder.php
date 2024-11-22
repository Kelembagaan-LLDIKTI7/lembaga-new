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
        $akademi = DB::table('bentuk_pts')->where('bentuk_nama', 'Akademi')->first();
        $akademiKomunitas = DB::table('bentuk_pts')->where('bentuk_nama', 'Akademi Komunitas')->first();
        $institut = DB::table('bentuk_pts')->where('bentuk_nama', 'Institut')->first();
        $politeknik = DB::table('bentuk_pts')->where('bentuk_nama', 'Politeknik')->first();
        $st = DB::table('bentuk_pts')->where('bentuk_nama', 'Sekolah Tinggi')->first();
        $universitas = DB::table('bentuk_pts')->where('bentuk_nama', 'Universitas')->first();

        $organisasi_ids = [1, 2, 3];

        $listJabatan = [
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Ketua',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $st->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Ketua',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $st->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $akademi->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $akademi->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $akademiKomunitas->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $akademiKomunitas->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $politeknik->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Direktur',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $politeknik->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Rektor',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $institut->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Rektor',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $institut->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Rektor',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $universitas->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Rektor',
                'jabatan_organisasi' => $organisasi_ids[2],
                'bentuk_pt' => $universitas->id,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Ketua Yayasan',
                'jabatan_organisasi' => $organisasi_ids[1],
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jabatan_nama' => 'Wakil Ketua Yayasan',
                'jabatan_organisasi' => $organisasi_ids[1],
                'created_at' => now(),
            ],
        ];

        foreach ($listJabatan as $jabatan) {
            DB::table('jabatans')->insert($jabatan);
        }
    }
}
