<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listKota = [
            ['id' => 1, 'provinsi_id' => 35, 'nama' => 'Surabaya', 'jumlah' => 126],
            ['id' => 2, 'provinsi_id' => 35, 'nama' => 'Malang', 'jumlah' => 80],
            ['id' => 3, 'provinsi_id' => 35, 'nama' => 'Sidoarjo', 'jumlah' => 16],
            ['id' => 4, 'provinsi_id' => 35, 'nama' => 'Jember', 'jumlah' => 16],
            ['id' => 5, 'provinsi_id' => 35, 'nama' => 'Kediri', 'jumlah' => 21],
            ['id' => 6, 'provinsi_id' => 35, 'nama' => 'Mojokerto', 'jumlah' => 14],
            ['id' => 7, 'provinsi_id' => 35, 'nama' => 'Gresik', 'jumlah' => 15],
            ['id' => 8, 'provinsi_id' => 35, 'nama' => 'Banyuwangi', 'jumlah' => 12],
            ['id' => 9, 'provinsi_id' => 35, 'nama' => 'Pasuruan', 'jumlah' => 10],
            ['id' => 10, 'provinsi_id' => 35, 'nama' => 'Ponorogo', 'jumlah' => 7],
            ['id' => 11, 'provinsi_id' => 35, 'nama' => 'Tuban', 'jumlah' => 9],
            ['id' => 12, 'provinsi_id' => 35, 'nama' => 'Probolinggo', 'jumlah' => 7],
            ['id' => 13, 'provinsi_id' => 35, 'nama' => 'Blitar', 'jumlah' => 6],
            ['id' => 14, 'provinsi_id' => 35, 'nama' => 'Madiun', 'jumlah' => 7],
            ['id' => 15, 'provinsi_id' => 35, 'nama' => 'Lumajang', 'jumlah' => 7],
            ['id' => 16, 'provinsi_id' => 35, 'nama' => 'Ngawi', 'jumlah' => 3],
            ['id' => 17, 'provinsi_id' => 35, 'nama' => 'Bojonegoro', 'jumlah' => 10],
            ['id' => 18, 'provinsi_id' => 35, 'nama' => 'Sumenep', 'jumlah' => 6],
            ['id' => 19, 'provinsi_id' => 35, 'nama' => 'Lamongan', 'jumlah' => 6],
            ['id' => 20, 'provinsi_id' => 35, 'nama' => 'Jombang', 'jumlah' => 18],
            ['id' => 21, 'provinsi_id' => 35, 'nama' => 'Magetan', 'jumlah' => 2],
            ['id' => 22, 'provinsi_id' => 35, 'nama' => 'Situbondo', 'jumlah' => 4],
            ['id' => 23, 'provinsi_id' => 35, 'nama' => 'Nganjuk', 'jumlah' => 7],
            ['id' => 24, 'provinsi_id' => 35, 'nama' => 'Bangkalan', 'jumlah' => 5],
            ['id' => 25, 'provinsi_id' => 35, 'nama' => 'Sampang', 'jumlah' => 3],
            ['id' => 26, 'provinsi_id' => 35, 'nama' => 'Trenggalek', 'jumlah' => 3],
            ['id' => 27, 'provinsi_id' => 35, 'nama' => 'Pamekasan', 'jumlah' => 4],
            ['id' => 28, 'provinsi_id' => 35, 'nama' => 'Tulungagung', 'jumlah' => 6],
            ['id' => 29, 'provinsi_id' => 35, 'nama' => 'Pacitan', 'jumlah' => 1],
            ['id' => 30, 'provinsi_id' => 35, 'nama' => 'Bondowoso', 'jumlah' => 2],
            ['id' => 31, 'provinsi_id' => 35, 'nama' => 'Batu', 'jumlah' => 1]
        ];

        foreach ($listKota as $kota) {
            DB::table('kotas')->insert([
                'id' => Str::uuid(),
                'nama' => $kota['nama'],
                'created_at' => now(),
            ]);
        }
    }
}
