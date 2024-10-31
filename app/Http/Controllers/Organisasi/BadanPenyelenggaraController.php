<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\Organisasi;
use Illuminate\Http\Request;

class BadanPenyelenggaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
            )
            ->orderBy('organisasi_nama', 'asc')
            ->get();
        return view('Organisasi.BadanPenyelenggara.Index', ['badanPenyelenggaras' => $badanPenyelenggaras]);
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
        // dd($id);
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_status',
                'organisasi_alamat',
                'organisasi_kota',
            )
            ->with(['children' => function ($query) {
                $query->select(
                    'id',
                    'organisasi_nama',
                    'organisasi_nama_singkat',
                    'organisasi_kode',
                    'organisasi_email',
                    'organisasi_telp',
                    'organisasi_kota',
                    'organisasi_alamat',
                    'organisasi_website',
                    'organisasi_logo',
                    'organisasi_status',
                    'organisasi_type_id',
                    'parent_id',
                );
            }])
            ->firstOrFail($id);

        // $kota = Kota::all();

        // $listKota = $kota->map(function ($item) {
        //     return [
        //         $item->nama,
        //     ];
        // });

        // dd($listKota);

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     // 'listKota' => $listKota
        // ]);

        return view('Organisasi.BadanPenyelenggara.Show', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            // 'listKota' => $listKota
        ]);
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
