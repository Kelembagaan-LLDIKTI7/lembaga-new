<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProgramStudi;
use App\Models\Organisasi;

class ProdiController extends Controller
{
    // Mendapatkan semua data prodi dengan filter dan pagination
    public function index(Request $request): JsonResponse
    {
        $query = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_kode as kode_pt',
                'organisasi_nama as nama_pt',
                'organisasi_kota as kota',
                'organisasi_bentuk_pt',
                'parent_id'
            )
            ->with(['parent:id,organisasi_nama', 'bentukPT:id,bentuk_nama'])
            ->with(['prodis' => function ($q) {
                $q->select('id_organization', 'prodi_kode', 'prodi_nama', 'prodi_jenjang')
                    ->whereHas('akreditasis', function ($akreditasiQuery) {
                        $akreditasiQuery->where('aktif', 'Ya');
                    })
                    ->with([
                        'akreditasis' => function ($akreditasiQuery) {
                            $akreditasiQuery->select(
                                'id',
                                'id_prodi',
                                'akreditasi_sk',
                                'akreditasi_tgl_akhir',
                                'id_peringkat_akreditasi'
                            )
                                ->where('aktif', 'Ya')
                                ->with('peringkat_akreditasi:id,peringkat_logo')
                                ->limit(1);
                        }
                    ]);
            }])
            ->orderBy('nama_pt', 'asc');

        // Apply filters for prodi name, jenjang, and region
        if ($request->has('name')) {
            $query->where('organisasi_nama', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->has('jenjang')) {
            $query->whereHas('prodis', function ($prodiQuery) use ($request) {
                $prodiQuery->where('prodi_jenjang', $request->input('jenjang'));
            });
        }

        if ($request->has('region')) {
            $query->where('organisasi_kota', $request->input('region'));
        }

        // Paginate the result
        $perguruanTinggi = $query->paginate(10);

        return response()->json($perguruanTinggi);
    }


    // Mendapatkan detail prodi tertentu berdasarkan ID
    public function show($id): JsonResponse
    {
        $perguruanTinggi = Organisasi::with(['bentukPT:id,bentuk_nama', 'prodis' => function ($q) {
            $q->select('kode_prodi', 'program_jenjang', 'kota', 'id_organization'); // Pastikan 'id_organization' ada di tabel program_studis
        }])->find($id);

        if (!$perguruanTinggi) {
            return response()->json(['message' => 'Prodi tidak ditemukan'], 404);
        }

        return response()->json($perguruanTinggi);
    }
}
