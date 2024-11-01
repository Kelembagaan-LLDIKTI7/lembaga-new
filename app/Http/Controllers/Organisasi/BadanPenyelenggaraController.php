<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Imports\BpImport;
use App\Models\Akta;
use App\Models\JenisSuratKeputusan;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\PimpinanOrganisasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        $kotas =  Kota::select(
            'id',
            'nama'
        )->get();

        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        return view('Organisasi.BadanPenyelenggara.Create', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'kotas' => $kotas,
            'jenis' => $jenis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
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

        $pimpinan = PimpinanOrganisasi::where('id_organization', $id)
            ->select('pimpinan_nama', 'pimpinan_email', 'pimpinan_status', 'id_jabatan')
            ->with([
                'jabatan' => function ($query) {
                    $query->select('id', 'jabatan_nama')->get();
                }
            ])->get();

        $akta = Akta::where('id_organization', $id)
            ->select(['id','akta_nomor', 'akta_tanggal', 'akta_status'])
            ->with(['skKumham'])
            ->get();
            
        // ->select('pimpinan_nama', 'pimpinan_email', 'pimpinan_status', 'id_jabatan')
        // ->with([
        //     'jabatan' => function ($query) {
        //         $query->select('id', 'jabatan_nama')->get();
        //     }
        // ])->get();
        // $kota = Kota::all();

        // $listKota = $kota->map(function ($item) {
        //     return [
        //         $item->nama,
        //     ];
        // });

        // dd($listKota);

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     'pimpinan' => $pimpinan,
        //     'akta' => $akta
        // ]);

        return view('Organisasi.BadanPenyelenggara.Show', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'pimpinan' => $pimpinan,
            'akta' => $akta,
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');

        Excel::import(new BpImport, $file);

        return redirect()->route('badan-penyelenggara.index')->with('success', 'Badan Penyelenggara berhasil disimpan.');
    }
}
