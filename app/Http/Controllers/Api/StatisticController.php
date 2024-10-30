<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProgramStudi;
use App\Models\Organisasi;
use App\Models\Kota;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function statistics(): JsonResponse
    {
        $totalPerguruanTinggi = Organisasi::count();
        $totalProdi = ProgramStudi::count();
        // $totalBentukPT = Organization::distinct('type')->count();
        $totalWilayah = Kota::count();


        return response()->json([
            'status' => '200 OK - Success',
            'data' => [
                'total_perguruan_tinggi' => $totalPerguruanTinggi,
                'total_prodi' => $totalProdi,
                // 'total_bentuk_pt' => $totalBentukPT,
                'total_wilayah' => $totalWilayah,
            ]
        ]);
    }

    // Jumlah berdasarkan bentuk wilayah
    public function jumlahPerguruanTinggiBerdasarkanWilayah(): JsonResponse
    {
        $data = Organisasi::select('organisasi_kota as kota', DB::raw('count(*) as total'))
            ->groupBy('organisasi_kota')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // Jumlah berdasarkan bentuk lembaga
    public function jumlahPerguruanTinggiBerdasarkanBentukLembaga(): JsonResponse
    {
        $data = Organisasi::select('bentuk_lembaga', DB::raw('count(*) as total'))
            ->groupBy('bentuk_lembaga')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // Jumlah prodi berdasarkan program pendidikan
    public function jumlahProdiBerdasarkanProgram(): JsonResponse
    {
        $data = ProgramStudi::select('program_pendidikan', DB::raw('count(*) as total'))
            ->groupBy('program_pendidikan')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
