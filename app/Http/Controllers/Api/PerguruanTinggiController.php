<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Organisasi;
use App\Models\ProgramStudi;
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
                'organisasi_kota',
                'organisasi_status',
                'organisasi_bentuk_pt',
                'parent_id'
            )
            ->with(['parent:id,organisasi_nama', 'bentukPT:id,bentuk_nama', 'prodis:id,prodi_nama,prodi_kode,prodi_jenjang,id_organization'])
            ->withCount('prodis')
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

        if ($request->has('program_studi')) {
            $query->whereHas('prodis', function ($q) use ($request) {
                $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
            });
        }

        $perguruanTinggi = $query->get();

        $prodiQuery = ProgramStudi::query()
            ->select('id', 'prodi_nama', 'prodi_kode', 'prodi_jenjang', 'id_organization');

        if ($request->has('kode_pt') || $request->has('nama_pt') || $request->has('bentuk_pt') || $request->has('kota') || $request->has('program_studi')) {
            $prodiQuery->whereHas('perguruanTinggi', function ($q) use ($request) {
                if ($request->has('kode_pt')) {
                    $q->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
                }
                if ($request->has('nama_pt')) {
                    $q->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
                }
                if ($request->has('bentuk_pt')) {
                    $q->whereHas('bentukPT', function ($q2) use ($request) {
                        $q2->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
                    });
                }
                if ($request->has('kota')) {
                    $q->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
                }
                if ($request->has('program_studi')) {
                    $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
                }
            });
        }

        // Dapatkan hasil query untuk prodi
        $prodis = $prodiQuery->get();

        $chartQuery = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->select('organisasi_bentuk_pt', DB::raw('count(*) as total'))
            ->groupBy('organisasi_bentuk_pt')
            ->with('bentukPT:id,bentuk_nama');

        if ($request->has('kode_pt')) {
            $chartQuery->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        if ($request->has('nama_pt')) {
            $chartQuery->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        if ($request->has('bentuk_pt')) {
            $chartQuery->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $chartQuery->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        if ($request->has('program_studi')) {
            $chartQuery->whereHas('prodis', function ($q) use ($request) {
                $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
            });
        }

        $chartData = $chartQuery->get()
            ->map(function ($item) {
                return [
                    'label' => $item->bentukPT->bentuk_nama ?? 'Unknown',
                    'count' => $item->total,
                ];
            });

        $prodiChartQuery = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->withCount('prodis')
            ->with('bentukPT:id,bentuk_nama');

        if ($request->has('kode_pt')) {
            $prodiChartQuery->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        if ($request->has('nama_pt')) {
            $prodiChartQuery->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        if ($request->has('bentuk_pt')) {
            $prodiChartQuery->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $prodiChartQuery->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        if ($request->has('program_studi')) {
            $prodiChartQuery->whereHas('prodis', function ($q) use ($request) {
                $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
            });
        }

        $prodiChart = $prodiChartQuery->get()
            ->groupBy('organisasi_bentuk_pt')
            ->map(function ($group) {
                $totalProdi = $group->sum('prodis_count');
                $bentukNama = $group->first()->bentukPT->bentuk_nama ?? 'Unknown';

                return [
                    'label' => $bentukNama,
                    'count' => $totalProdi,
                ];
            })
            ->values();

        $prodiJenjangChartQuery = ProgramStudi::query()
            ->select('prodi_jenjang', DB::raw('count(*) as total'))
            ->groupBy('prodi_jenjang');

        if ($request->has('kode_pt')) {
            $prodiJenjangChartQuery->whereHas('perguruanTinggi', function ($q) use ($request) {
                $q->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
            });
        }

        if ($request->has('nama_pt')) {
            $prodiJenjangChartQuery->whereHas('perguruanTinggi', function ($q) use ($request) {
                $q->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
            });
        }

        if ($request->has('bentuk_pt')) {
            $prodiJenjangChartQuery->whereHas('perguruanTinggi.bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $prodiJenjangChartQuery->whereHas('perguruanTinggi', function ($q) use ($request) {
                $q->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
            });
        }

        if ($request->has('program_studi')) {
            $prodiJenjangChartQuery->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
        }

        $prodiJenjangChart = $prodiJenjangChartQuery->get()
            ->map(function ($item) {
                return [
                    'label' => $item->prodi_jenjang,
                    'count' => $item->total,
                ];
            });

        $cityChartQuery = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->select('organisasi_kota', DB::raw('count(*) as total'))
            ->groupBy('organisasi_kota');

        if ($request->has('kode_pt')) {
            $cityChartQuery->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        if ($request->has('nama_pt')) {
            $cityChartQuery->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        if ($request->has('bentuk_pt')) {
            $cityChartQuery->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $cityChartQuery->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        if ($request->has('program_studi')) {
            $cityChartQuery->whereHas('prodis', function ($q) use ($request) {
                $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
            });
        }

        $cityChartData = $cityChartQuery->get()
            ->map(function ($item) {
                return [
                    'label' => $item->organisasi_kota,
                    'count' => $item->total,
                ];
            });

        $totalInstitutions = Organisasi::where('organisasi_type_id', 3)->count();

        $cityChartQueryPersen = Organisasi::query()
            ->where('organisasi_type_id', 3)
            ->select('organisasi_kota', DB::raw('count(*) as total'))
            ->groupBy('organisasi_kota');

        if ($request->has('kode_pt')) {
            $cityChartQueryPersen->where('organisasi_kode', 'LIKE', '%' . $request->input('kode_pt') . '%');
        }

        if ($request->has('nama_pt')) {
            $cityChartQueryPersen->where('organisasi_nama', 'LIKE', '%' . $request->input('nama_pt') . '%');
        }

        if ($request->has('bentuk_pt')) {
            $cityChartQueryPersen->whereHas('bentukPT', function ($q) use ($request) {
                $q->where('bentuk_nama', 'LIKE', '%' . $request->input('bentuk_pt') . '%');
            });
        }

        if ($request->has('kota')) {
            $cityChartQueryPersen->where('organisasi_kota', 'LIKE', '%' . $request->input('kota') . '%');
        }

        if ($request->has('program_studi')) {
            $cityChartQueryPersen->whereHas('prodis', function ($q) use ($request) {
                $q->where('prodi_jenjang', 'LIKE', '%' . $request->input('program_studi') . '%');
            });
        }

        $cityChartDataPersen = $cityChartQuery->get()
            ->map(function ($item) use ($totalInstitutions) {
                $percentage = ($item->total / $totalInstitutions) * 100;
                return [
                    'label' => $item->organisasi_kota,
                    'count' => $item->total,
                    'percentage' => round($percentage, 1)
                ];
            });

        return response()->json([
            'perguruanTinggi' => $perguruanTinggi,
            'prodis' => $prodis,
            'chartData' => $chartData,
            'prodiChart' => $prodiChart,
            'prodiJenjangChart' => $prodiJenjangChart,
            'cityChartData' => $cityChartData,
            'cityChartDataPersen' => $cityChartDataPersen,
        ]);
    }

    public function show($id): JsonResponse
    {
        $perguruanTinggi = Organisasi::with('bentukPT:id,bentuk_nama')->find($id);

        if (!$perguruanTinggi) {
            return response()->json(['message' => 'Perguruan Tinggi tidak ditemukan'], 404);
        }

        return response()->json($perguruanTinggi);
    }
}
