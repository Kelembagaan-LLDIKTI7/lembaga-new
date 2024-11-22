<?php

namespace Database\Seeders;

use App\Models\OrganisasiType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisasiTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization_types = [
            [
                'organisasi_type_nama' => 'Lembaga Layanan Pendidikan Tinggi Wilayah VII'
            ],
            [
                'organisasi_type_nama' => 'Badan penyelenggara'
            ],
            [
                'organisasi_type_nama' => 'Perguruan Tinggi'
            ]
        ];

        OrganisasiType::insert($organization_types);
    }
}
