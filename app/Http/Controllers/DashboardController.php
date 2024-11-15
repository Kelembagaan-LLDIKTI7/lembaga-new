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

        if ($user->hasRole('Badan Penyelenggara')) {
            $perguruanTinggi = Organisasi::where('organisasi_type_id', 3)
                ->where('parent_id', $user->id_organization)
                ->count();
            $programStudi = ProgramStudi::where('prodi_active_status', 'Aktif')
                ->whereHas('perguruanTinggi', function ($query) use ($user) {
                    $query->where('parent_id', $user->id_organization);
                })->count();
            $bentukPtCounts = BentukPt::select('id', 'bentuk_nama')
                ->withCount(['organisasi' => function ($query) use ($user) {
                    $query->where('organisasi_type_id', 3)
                        ->where('parent_id', $user->id_organization);
                }])->get();
            $perkaras = Perkara::where('status', 'Berjalan')
                ->where(function ($query) use ($user) {
                    $query->where('id_organization', $user->id_organization)
                        ->orWhereIn('id_organization', function ($subQuery) use ($user) {
                            $subQuery->select('id')
                                ->from('organisasis')
                                ->where('parent_id', $user->id_organization)
                                ->where('organisasi_type_id', 3);
                        })
                        ->orWhereIn('id_prodi', function ($subQuery) use ($user) {
                            $subQuery->select('program_studis.id')
                                ->from('program_studis')
                                ->join('organisasis as pt', 'program_studis.id_organization', '=', 'pt.id')
                                ->where('pt.parent_id', $user->id_organization)
                                ->where('pt.organisasi_type_id', 3);
                        });
                })
                ->select('id', 'title', 'tanggal_kejadian', 'status')
                ->orderBy('created_at', 'desc')
                ->get();
            $programStudiCounts = BentukPt::select('id', 'bentuk_nama')
                ->with(['organisasi' => function ($query) use ($user) {
                    $query->select('id', 'organisasi_bentuk_pt', 'organisasi_type_id')
                        ->where('organisasi_type_id', 3)
                        ->where('parent_id', $user->id_organization)
                        ->whereHas('prodis', function ($query) {
                            $query->where('prodi_active_status', 'Aktif');
                        });
                }])
                ->withCount(['organisasi as program_studi_count' => function ($query) use ($user) {
                    $query->where('organisasi_type_id', 3)
                        ->where('parent_id', $user->id_organization)
                        ->whereHas('prodis', function ($query) {
                            $query->where('prodi_active_status', 'Aktif');
                        });
                }])->get();
        }
        if ($user->hasRole('Perguruan Tinggi')) {
            $perguruanTinggi = Organisasi::where('id', $user->id_organization)
                ->where('organisasi_type_id', 3)
                ->count();
            $programStudi = ProgramStudi::where('prodi_active_status', 'Aktif')
                ->where('id_organization', $user->id_organization)
                ->count();
            $bentukPtCounts = BentukPt::select('id', 'bentuk_nama')
                ->withCount(['organisasi' => function ($query) use ($user) {
                    $query->where('organisasi_type_id', 3)
                        ->where('id', $user->id_organization);
                }])->get();
            $perkaras = Perkara::where('status', 'Berjalan')
                ->where(function ($query) use ($user) {
                    $query->whereRaw("CONVERT(`id_organization` USING utf8mb4) COLLATE utf8mb4_unicode_ci = ?", [$user->id_organization])
                        ->orWhereIn('id_prodi', function ($subQuery) use ($user) {
                            $subQuery->selectRaw("CONVERT(`id` USING utf8mb4) COLLATE utf8mb4_unicode_ci")
                                ->from('program_studis')
                                ->whereRaw("CONVERT(`id_organization` USING utf8mb4) COLLATE utf8mb4_unicode_ci = ?", [$user->id_organization]);
                        });
                })
                ->select('id', 'title', 'tanggal_kejadian', 'status')
                ->orderBy('created_at', 'desc')
                ->get();

            $programStudiCounts = BentukPt::select('id', 'bentuk_nama')
                ->with(['organisasi' => function ($query) use ($user) {
                    $query->select('id', 'organisasi_bentuk_pt', 'organisasi_type_id')
                        ->where('organisasi_type_id', 3)
                        ->where('parent_id', $user->id_organization)
                        ->whereHas('prodis', function ($query) {
                            $query->where('prodi_active_status', 'Aktif');
                        });
                }])
                ->withCount(['organisasi as program_studi_count' => function ($query) use ($user) {
                    $query->where('organisasi_type_id', 3)
                        ->where('parent_id', $user->id_organization)
                        ->whereHas('prodis', function ($query) {
                            $query->where('prodi_active_status', 'Aktif');
                        });
                }])->get();
        }

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
