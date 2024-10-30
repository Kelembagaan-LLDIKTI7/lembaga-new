<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProgramStudi;
use App\Models\Organisasi;

class StatisticController extends Controller
{
    public function statistics(): JsonResponse
    {
        $totalPerguruanTinggi = Organisasi::count();
        // $totalProdi = ProgramStudi::count();
        // $totalBentukPT = Organization::distinct('type')->count(); // Asumsikan ada kolom 'type' untuk bentuk PT
        // $totalWilayah = Organization::distinct('region')->count(); // Asumsikan ada kolom 'region' untuk wilayah

        return response()->json([
            'status' => '200 OK - Success',
            'data' => [
                'total_perguruan_tinggi' => $totalPerguruanTinggi,
                // 'total_prodi' => $totalProdi,
                // 'total_bentuk_pt' => $totalBentukPT,
                // 'total_wilayah' => $totalWilayah,
            ]
        ]);
    }
}
