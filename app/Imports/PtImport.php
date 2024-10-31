<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

                $id_pt = Str::uuid()->toString();

                DB::table('organisasis')->insert([
                    'id' => $id_pt,
                    'organisasi_nama' => $rowArray[0],
                    'organisasi_alamat' => $rowArray[1],
                    'organisasi_kota' => $rowArray[2],
                    'organisasi_telp' => $rowArray[3],
                    'organisasi_email' => $rowArray[4],
                    'organisasi_status' => 'Aktif',
                    'organisasi_type_id' => 3,
                    'users_id' => Auth::user()->id,
                    'created_at' => now(),
                ]);

                $peringkat = DB::table('peringkat_akreditasis')->where('peringkat_nama', $rowArray[6])->first();

                DB::table('akreditasis')->insert([
                    'id' => Str::uuid()->toString(),
                    'akreditasi_sk' => $rowArray[5],
                    'akreditasi_tgl_akhir' => $rowArray[7],
                    'id_peringkat_akreditasi' => $peringkat->id,
                    'id_organization' => $id_pt,
                    'id_user' => Auth::user()->id,
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
