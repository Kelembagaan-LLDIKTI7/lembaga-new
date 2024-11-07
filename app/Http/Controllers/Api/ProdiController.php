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
            ->where('organisasi_type_id', 3) // Menambahkan filter untuk jenis organisasi
            ->select(
                'id',
                'organisasi_kode as kode_pt',
                'organisasi_nama as nama_pt',
                'organisasi_kota as kota',
                'organisasi_bentuk_pt',
                'parent_id'
            )
            ->with(['parent:id,organisasi_nama', 'bentukPT:id,bentuk_nama', 'prodis' => function($q) {
                $q->select('id_organization', 'prodi_kode', 'prodi_nama', 'prodi_jenjang');
            }])
            ->orderBy('nama_pt', 'asc');

        // Filter berdasarkan nama prodi
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Filter berdasarkan jenjang
        if ($request->has('jenjang')) {
            $query->where('jenjang', $request->input('jenjang'));
        }

        // Filter berdasarkan wilayah (asumsikan 'region' adalah kolom terkait wilayah)
        if ($request->has('region')) {
            $query->where('region', $request->input('region'));
        }

        // Pagination dengan 10 data per halaman
        $perguruanTinggi = $query->paginate(10);

        return response()->json($perguruanTinggi);
    }

    // Mendapatkan detail prodi tertentu berdasarkan ID
    public function show($id): JsonResponse
    {
        $perguruanTinggi = Organisasi::with(['bentukPT:id,bentuk_nama', 'prodis' => function($q) {
            $q->select('kode_prodi', 'program_jenjang', 'kota', 'id_organization'); // Pastikan 'id_organization' ada di tabel program_studis
        }])->find($id);

        if (!$perguruanTinggi) {
            return response()->json(['message' => 'Prodi tidak ditemukan'], 404);
        }

        return response()->json($perguruanTinggi);
    }
}
