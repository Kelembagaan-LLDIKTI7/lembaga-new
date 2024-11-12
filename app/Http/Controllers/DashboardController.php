<?php

namespace App\Http\Controllers;

use App\Models\BentukPt;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\Perkara;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perkaras = Perkara::where('status', 'Berjalan')
            ->select('id', 'title', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        $perguruanTinggi = Organisasi::where('organisasi_type_id', 3)->count();

        $programStudi = ProgramStudi::where('prodi_active_status', 'Aktif')->count();

        $bentukPt = BentukPt::count();

        $kota = Kota::count();
        // return response()->json([
        //     'perkaras' => $perkaras,
        //     'perguruanTinggi' => $perguruanTinggi,
        //     'programStudi' => $programStudi,
        // ]);

        return view('Dashboard.Index', [
            'perkaras' => $perkaras,
            'perguruanTinggi' => $perguruanTinggi,
            'programStudi' => $programStudi,
            'bentukPt' => $bentukPt,
            'kota' => $kota,
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
