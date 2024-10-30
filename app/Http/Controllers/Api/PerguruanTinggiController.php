<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Organisasi;

class PerguruanTinggiController extends Controller
{
    // Mendapatkan semua data perguruan tinggi dengan pencarian dan pagination
    public function index(Request $request): JsonResponse
    {
        $query = Organisasi::query();

        // Filter berdasarkan nama perguruan tinggi
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Filter berdasarkan jenjang
        if ($request->has('jenjang')) {
            $query->where('jenjang', $request->input('jenjang'));
        }

        // Filter berdasarkan bentuk PT
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter berdasarkan wilayah
        if ($request->has('region')) {
            $query->where('region', $request->input('region'));
        }

        // Pagination dengan 10 data per halaman
        $perguruanTinggi = $query->paginate(10);

        return response()->json($perguruanTinggi);
    }

    // Mendapatkan detail perguruan tinggi tertentu berdasarkan ID
    public function show($id): JsonResponse
    {
        $perguruanTinggi = Organisasi::find($id);

        if (!$perguruanTinggi) {
            return response()->json(['message' => 'Perguruan Tinggi tidak ditemukan'], 404);
        }

        return response()->json($perguruanTinggi);
    }
}
