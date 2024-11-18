<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProgramStudi;
use App\Models\Organisasi;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function statistics(Request $request): JsonResponse
    {
        // Menghitung total berdasarkan filter yang diterapkan
        $queryPT = Organisasi::query()->where('organisasi_type_id', 3);
        $queryProdi = ProgramStudi::query();

        // Menerapkan filter jika tersedia
        if ($request->filled('organisasi_nama')) {
            $queryPT->where('organisasi_nama', 'LIKE', '%' . $request->input('organisasi_nama') . '%');
        }

        if ($request->filled('program')) {
            $queryProdi->where('prodi_jenjang', $request->input('program'));
        }

        if ($request->filled('wilayah')) {
            $queryPT->where('organisasi_kota', 'LIKE', '%' . $request->input('wilayah') . '%');
        }

        // Filter berdasarkan bentuk PT
        if ($request->filled('bentuk_pt')) {
            $queryPT->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        // Menghitung total sesuai dengan filter
        $totalPerguruanTinggi = $queryPT->count();
        $totalProdi = $queryProdi->where('prodi_active_status', 'Aktif') ->count();
        $totalBentukPT = Organisasi::distinct('organisasi_bentuk_pt')->where('organisasi_type_id', 3)->count();
        $totalWilayah = Organisasi::distinct('organisasi_kota')->where('organisasi_type_id', 3)->count();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => [
                'total_perguruan_tinggi' => $totalPerguruanTinggi,
                'total_bentuk_pt' => $totalBentukPT,
                'total_prodi' => $totalProdi,
                'total_wilayah' => $totalWilayah,
            ]
        ]);
    }

    // Jumlah berdasarkan bentuk PT
    public function jumlahPerguruanTinggiBerdasarkanBentukPT(Request $request): JsonResponse
    {
        $data = Organisasi::with('bentukPT')
            ->select('organisasi_bentuk_pt', DB::raw('count(*) as total'))
            ->where('organisasi_type_id', 3)
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', 'LIKE', '%' . $request->input('organisasi_nama') . '%');
            })
            ->groupBy('organisasi_bentuk_pt')
            ->get()
            ->map(function ($item) {
                return [
                    'bentuk_nama' => $item->bentukPT->bentuk_nama,
                    'total' => $item->total,
                ];
            });

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }

    // Jumlah berdasarkan bentuk wilayah
    public function jumlahPerguruanTinggiBerdasarkanWilayah(Request $request): JsonResponse
    {
        $data = Organisasi::select('organisasi_kota as kota', DB::raw('count(*) as total'))
            ->where('organisasi_type_id', 3)
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', 'LIKE', '%' . $request->input('organisasi_nama') . '%');
            })
            ->groupBy('organisasi_kota')
            ->get();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }

    // Jumlah prodi berdasarkan program pendidikan
    public function jumlahProdiBerdasarkanProgram(Request $request): JsonResponse
    {
        $data = ProgramStudi::select('prodi_nama', 'prodi_jenjang', DB::raw('count(*) as total'))
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', 'LIKE', '%' . $request->input('organisasi_nama') . '%');
            })
            ->groupBy('prodi_nama', 'prodi_jenjang')
            ->get();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }
}
