<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class BpImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowArray = $row->toArray();

                $id = Str::uuid()->toString();

                DB::table('organisasis')->updateOrInsert([
                    'organisasi_nama' => $rowArray[0],
                ], [
                    'id' => $id,
                    'organisasi_nama' => $rowArray[0],
                    'organisasi_status' => 'Aktif',
                    'organisasi_type_id' => 2,
                    'users_id' => Auth::user()->id,
                    'created_at' => now(),
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
