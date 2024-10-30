<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProgramStudi;

class ProdiController extends Controller
{
    // Mendapatkan semua data prodi dengan filter dan pagination
    public function index(Request $request): JsonResponse
    {
        $query = ProgramStudi::query();

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
        $prodi = $query->paginate(10);

        return response()->json($prodi);
    }

    // Mendapatkan detail prodi tertentu berdasarkan ID
    public function show($id): JsonResponse
    {
        $prodi = ProgramStudi::find($id);

        if (!$prodi) {
            return response()->json(['message' => 'Prodi tidak ditemukan'], 404);
        }

        return response()->json($prodi);
    }
}
