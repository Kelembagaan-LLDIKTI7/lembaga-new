<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Organisasi;
use Illuminate\Support\Facades\DB;

class PerguruanTinggiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_kode',
                'organisasi_nama as pt_nama',
                'organisasi_nama_singkat',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
                'organisasi_bentuk_pt',
                'parent_id'
            )
            ->with(['parent:id,organisasi_nama', 'bentukPT:id,bentuk_nama'])
            ->orderBy('pt_nama', 'asc');

        if ($request->has('kode_pt')) {
            $query->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        if ($request->has('nama_pt')) {
            $query->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        if ($request->has('bentuk_pt')) {
            $query->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $query->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        $perguruanTinggi = $query->paginate(10);

        $totalPerguruanTinggi = Organisasi::where('organisasi_type_id', 3)->count();

        $totalProgramStudi = Organisasi::where('organisasi_type_id', 3)
            ->withCount('prodis')
            ->get()
            ->sum('prodis_count');

        $totalBentukPerguruanTinggi = Organisasi::where('organisasi_type_id', 3)
            ->distinct('organisasi_bentuk_pt')
            ->count('organisasi_bentuk_pt');

        $totalWilayah = Organisasi::where('organisasi_type_id', 3)
            ->distinct('organisasi_kota')
            ->count('organisasi_kota');

        return response()->json([
            'perguruanTinggi' => $perguruanTinggi,
            'Perguruan Tinggi' => $totalPerguruanTinggi,
            'Program Studi' => $totalProgramStudi,
            'Bentuk Perguruan Tinggi' => $totalBentukPerguruanTinggi,
            'Wilayah' => $totalWilayah,
        ]);
    }

    // Mendapatkan detail perguruan tinggi tertentu berdasarkan ID
    public function show($id): JsonResponse
    {
        $perguruanTinggi = Organisasi::with('bentukPT:id,bentuk_nama')->find($id);

        if (!$perguruanTinggi) {
            return response()->json(['message' => 'Perguruan Tinggi tidak ditemukan'], 404);
        }

        return response()->json($perguruanTinggi);
    }
}
