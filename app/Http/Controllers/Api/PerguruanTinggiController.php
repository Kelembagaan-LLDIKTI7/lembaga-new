<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Organisasi;
use Illuminate\Support\Facades\DB;

class PerguruanTinggiController extends Controller
{
    // Mendapatkan semua data perguruan tinggi dengan pencarian dan pagination
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

        // Filter berdasarkan kode perguruan tinggi
        if ($request->has('kode_pt')) {
            $query->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        // Filter berdasarkan nama perguruan tinggi
        if ($request->has('nama_pt')) {
            $query->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        // Filter berdasarkan bentuk PT berdasarkan bentuk_pt_nama
        if ($request->has('bentuk_pt')) {
            $query->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        // Filter berdasarkan kota
        if ($request->has('kota')) {
            $query->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        // Pagination dengan 10 data per halaman
        $perguruanTinggi = $query->paginate(10);

        $chartData = Organisasi::where('organisasi_type_id', 3)
            ->with('bentukPT:id,bentuk_nama')
            ->select('organisasi_bentuk_pt', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('organisasi_bentuk_pt')
            ->get()
            ->map(function ($item) {
                return [
                    'bentuk_pt' => $item->bentukPT->bentuk_nama ?? 'Tidak Diketahui',
                    'jumlah' => $item->jumlah,
                ];
            });

        return response()->json([
            'perguruanTinggi' => $perguruanTinggi,
            'chartData' => $chartData,
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
