<?php

namespace Database\Seeders;

use App\Models\Organisasi;
use App\Models\OrganisasiType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_nama' => 'Lembaga Layanan Pendidikan Tinggi Wilayah VII',
            'organisasi_nama_singkat' => 'LLDIKTI VII',
            'organisasi_email' => 'ult.lldikti7@kemdikbud.go.id',
            'organisasi_telp' => '0315925419',
            'organisasi_kota' => 'Surabaya',
            'organisasi_alamat' => 'Jl. Dr. Ir. H. Soekarno No.177, Klampis Ngasem, Kec. Sukolilo, Kota SBY, Jawa Timur 60117',
            'organisasi_website' => 'https://lldikti7.kemdikbud.go.id/',
            'organisasi_logo' => 'test.png',
            'organisasi_status' => 'Aktif',
            'organisasi_type_id' => 1,
        ]);

        $org2 = Organisasi::create([
            'id' => '9dc07bcb-4bd2-4be7-b433-c29fbdeaa3fa',
            'organisasi_nama' => 'yayasan itats',
            'organisasi_nama_singkat' => 'yayasanITats',
            'organisasi_kode' => '000002',
            'organisasi_email' => 'yayasan@itats.ac.id',
            'organisasi_telp' => '0315925419',
            'organisasi_kota' => 'Surabaya',
            'organisasi_alamat' => 'Jl. Dr. Ir. H. Soekarno No.177, Klampis Ngasem, Kec. Sukolilo, Kota SBY, Jawa Timur 60117',
            'organisasi_website' => 'https://itats.ac.id/',
            'organisasi_logo' => 'test.png',
            'organisasi_status' => 'Aktif',
            'organisasi_type_id' => 2,
        ]);

        Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_nama' => 'Itats Surabaya',
            'organisasi_nama_singkat' => 'Itats',
            'organisasi_kode' => '000003',
            'organisasi_email' => 'itats@itats.ac.id',
            'organisasi_telp' => '0315925419',
            'organisasi_kota' => 'Surabaya',
            'organisasi_alamat' => 'Jl. Dr. Ir. H. Soekarno No.177, Klampis Ngasem, Kec. Sukolilo, Kota SBY, Jawa Timur 60117',
            'organisasi_website' => 'https://itats.ac.id/',
            'organisasi_logo' => 'test.png',
            'organisasi_status' => 'Aktif',
            'parent_id' => $org2->id,
            'organisasi_type_id' => 3,
        ]);
    }
}
