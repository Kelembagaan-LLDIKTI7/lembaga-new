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
            [
                'id' => 3501,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN PACITAN'
            ],
            [
                'id' => 3502,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN PONOROGO'
            ],
            [
                'id' => 3503,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN TRENGGALEK'
            ],
            [
                'id' => 3504,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN TULUNGAGUNG'
            ],
            [
                'id' => 3505,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN BLITAR'
            ],
            [
                'id' => 3506,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN KEDIRI'
            ],
            [
                'id' => 3507,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN MALANG'
            ],
            [
                'id' => 3508,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN LUMAJANG'
            ],
            [
                'id' => 3509,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN JEMBER'
            ],
            [
                'id' => 3510,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN BANYUWANGI'
            ],
            [
                'id' => 3511,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN BONDOWOSO'
            ],
            [
                'id' => 3512,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN SITUBONDO'
            ],
            [
                'id' => 3513,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN PROBOLINGGO'
            ],
            [
                'id' => 3514,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN PASURUAN'
            ],
            [
                'id' => 3515,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN SIDOARJO'
            ],
            [
                'id' => 3516,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN MOJOKERTO'
            ],
            [
                'id' => 3517,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN JOMBANG'
            ],
            [
                'id' => 3518,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN NGANJUK'
            ],
            [
                'id' => 3519,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN MADIUN'
            ],
            [
                'id' => 3520,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN MAGETAN'
            ],
            [
                'id' => 3521,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN NGAWI'
            ],
            [
                'id' => 3522,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN BOJONEGORO'
            ],
            [
                'id' => 3523,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN TUBAN'
            ],
            [
                'id' => 3524,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN LAMONGAN'
            ],
            [
                'id' => 3525,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN GRESIK'
            ],
            [
                'id' => 3526,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN BANGKALAN'
            ],
            [
                'id' => 3527,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN SAMPANG'
            ],
            [
                'id' => 3528,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN PAMEKASAN'
            ],
            [
                'id' => 3529,
                'provinsi_id' => 35,
                'nama' => 'KABUPATEN SUMENEP'
            ],
            [
                'id' => 3571,
                'provinsi_id' => 35,
                'nama' => 'KOTA KEDIRI'
            ],
            [
                'id' => 3572,
                'provinsi_id' => 35,
                'nama' => 'KOTA BLITAR'
            ],
            [
                'id' => 3573,
                'provinsi_id' => 35,
                'nama' => 'KOTA MALANG'
            ],
            [
                'id' => 3574,
                'provinsi_id' => 35,
                'nama' => 'KOTA PROBOLINGGO'
            ],
            [
                'id' => 3575,
                'provinsi_id' => 35,
                'nama' => 'KOTA PASURUAN'
            ],
            [
                'id' => 3576,
                'provinsi_id' => 35,
                'nama' => 'KOTA MOJOKERTO'
            ],
            [
                'id' => 3577,
                'provinsi_id' => 35,
                'nama' => 'KOTA MADIUN'
            ],
            [
                'id' => 3578,
                'provinsi_id' => 35,
                'nama' => 'KOTA SURABAYA'
            ],
            [
                'id' => 3579,
                'provinsi_id' => 35,
                'nama' => 'KOTA BATU'
            ],
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
