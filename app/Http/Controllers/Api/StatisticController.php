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
        $queryPT = Organisasi::query();
        $queryProdi = ProgramStudi::query();

        // Menerapkan filter jika tersedia
        if ($request->filled('organisasi_nama')) {
            $queryPT->where('organisasi_nama', $request->input('organisasi_nama'));
        }

        if ($request->filled('program')) {
            $queryProdi->where('prodi_jenjang', $request->input('program'));
        }

        if ($request->filled('wilayah')) {
            $queryPT->where('organisasi_kota', $request->input('wilayah'));
        }

        // Menghitung total sesuai dengan filter
        $totalPerguruanTinggi = $queryPT->count();
        $totalProdi = $queryProdi->count();
        $totalWilayah = Organisasi::distinct('organisasi_kota')->count();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => [
                'total_perguruan_tinggi' => $totalPerguruanTinggi,
                'total_prodi' => $totalProdi,
                'total_wilayah' => $totalWilayah,
            ]
        ]);
    }

    // Jumlah berdasarkan bentuk wilayah
    public function jumlahPerguruanTinggiBerdasarkanWilayah(Request $request): JsonResponse
    {
        $data = Organisasi::select('organisasi_kota as kota', DB::raw('count(*) as total'))
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', $request->input('organisasi_nama'));
            })
            ->groupBy('organisasi_kota')
            ->get();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }

    // Jumlah berdasarkan bentuk lembaga
    public function jumlahPerguruanTinggiBerdasarkanBentukLembaga(Request $request): JsonResponse
    {
        $data = Organisasi::select('bentuk_lembaga', DB::raw('count(*) as total'))
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', $request->input('organisasi_nama'));
            })
            ->groupBy('bentuk_lembaga')
            ->get();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }

    // Jumlah prodi berdasarkan program pendidikan
    public function jumlahProdiBerdasarkanProgram(Request $request): JsonResponse
    {
        $data = ProgramStudi::select('prodi_jenjang', DB::raw('count(*) as total'))
            ->when($request->filled('organisasi_nama'), function ($query) use ($request) {
                $query->where('organisasi_nama', $request->input('organisasi_nama'));
            })
            ->groupBy('prodi_jenjang')
            ->get();

        return response()->json([
            'status' => '200 OK - Success',
            'data' => $data
        ]);
    }
}
