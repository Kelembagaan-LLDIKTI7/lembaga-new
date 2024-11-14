<?php

namespace App\Http\Controllers;

use App\Models\BentukPt;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\Perkara;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $perkaras = Perkara::where('status', 'Berjalan')
            ->select('id', 'title', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        $perguruanTinggi = Organisasi::where('organisasi_type_id', 3)->count();
        $programStudi = ProgramStudi::where('prodi_active_status', 'Aktif')->count();
        $bentukPt = BentukPt::count();
        $kota = Kota::count();

        $bentukPtCounts = BentukPt::select('id', 'bentuk_nama')
            ->withCount(['organisasi' => function ($query) {
                $query->where('organisasi_type_id', 3);
            }])->get();

        $programStudiCounts = BentukPt::select('id', 'bentuk_nama')
            ->with(['organisasi' => function ($query) {
                $query->where('organisasi_type_id', 3)
                    ->select('id', 'organisasi_bentuk_pt')
                    ->withCount(['prodis as active_program_studi_count' => function ($subQuery) {
                        $subQuery->where('prodi_active_status', 'Aktif');
                    }]);
            }])
            ->get();

        $programStudiCounts = $programStudiCounts->map(function ($bentukPt) {
            $bentukPt->total_program_studi = $bentukPt->organisasi->sum('active_program_studi_count');
            return $bentukPt;
        });

        $jenjangList = ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'];

        $programPendidikanCounts = ProgramStudi::select('prodi_jenjang')
            ->where('prodi_active_status', 'Aktif')
            ->when($user->hasRole('Badan Penyelenggara'), function ($query) use ($user) {
                $query->whereHas('perguruanTinggi', function ($query) use ($user) {
                    $query->where('parent_id', $user->id_organization);
                });
            })
            ->when($user->hasRole('Perguruan Tinggi'), function ($query) use ($user) {
                $query->whereHas('perguruanTinggi', function ($query) use ($user) {
                    $query->where('id', $user->id_organization); 
                });
            })
            ->groupBy('prodi_jenjang')
            ->selectRaw('prodi_jenjang, COUNT(*) as count')
            ->pluck('count', 'prodi_jenjang')
            ->toArray();

        foreach ($jenjangList as $jenjang) {
            if (!isset($programPendidikanCounts[$jenjang])) {
                $programPendidikanCounts[$jenjang] = 0;
            }
        }

        $programPendidikanCounts = collect($programPendidikanCounts)
            ->only($jenjangList)
            ->toArray();

        return view('Dashboard.Index', [
            'perkaras' => $perkaras,
            'perguruanTinggi' => $perguruanTinggi,
            'programStudi' => $programStudi,
            'bentukPt' => $bentukPt,
            'kota' => $kota,
            'bentukPtCounts' => $bentukPtCounts,
            'programStudiCounts' => $programStudiCounts,
            'programPendidikanCounts' => $programPendidikanCounts,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perkaras = Perkara::where('id', $id)
            ->select('id', 'title', 'tanggal_kejadian', 'status', 'deskripsi_kejadian', 'bukti_foto', 'id_organization', 'id_prodi')
            ->with([
                'organisasi' => function ($query) {
                    $query->select('id', 'organisasi_nama', 'organisasi_status', 'organisasi_type_id');
                },
                'prodi' => function ($query) {
                    $query->select('id', 'prodi_nama', 'prodi_jenjang', 'prodi_active_status');
                }
            ])->first();

        if ($perkaras && $perkaras->id_organization) {
            return $perkaras->organisasi && $perkaras->organisasi->organisasi_type_id == 2
                ? redirect()->route('perkara-organisasi.show', $perkaras->id)
                : redirect()->route('perkara-organisasipt.show', $perkaras->id);
        }

        if ($perkaras && $perkaras->id_prodi) {
            return redirect()->route('perkara-prodi.show', $perkaras->id);
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
