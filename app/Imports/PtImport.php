<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class PtImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowArray = $row->toArray();

                $id = Str::uuid()->toString();

                DB::table('organisasis')->insert([
                    'id' => $id,
                    'org_nama' => $rowArray[0],
                    'org_nama_singkat' => $this->singkatNama($rowArray[0]),
                    'org_alamat' => $rowArray[1],
                    'org_kota' => $rowArray[2],
                    'org_telp' => $rowArray[3],
                    'org_email' => $rowArray[4],
                    'org_status' => 'Aktif',
                    'org_type_id' => 3,
                ]);

                Log::info('Data ke-' . $index . ' berhasil diimport');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function singkatNama($nama)
    {
        $kataKata = explode(' ', $nama);
        $singkatan = '';

        foreach ($kataKata as $kata) {
            if (preg_match('/\d/', $kata)) {
                $singkatan .= preg_replace('/[^0-9]/', '', $kata);
            } else {
                $singkatan .= strtoupper(substr($kata, 0, 1));
            }
        }

        return $singkatan;
    }
}
