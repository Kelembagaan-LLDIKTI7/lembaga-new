<?php

namespace App\Http\Controllers\Akreditasi;

use App\Http\Controllers\Controller;
use App\Models\Akreditasi;
use App\Models\LembagaAkreditasi;
use App\Models\PeringkatAkreditasi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkreditasiProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $prodi = ProgramStudi::select('id')->findOrFail($id);
        $peringkat = PeringkatAkreditasi::select('id', 'peringkat_nama')->orderBy('peringkat_nama', 'asc')->get();
        $lembaga = LembagaAkreditasi::select('id', 'lembaga_nama')->orderBy('lembaga_nama', 'asc')->get();
        return view('Akreditasi.ProgramStudi.Create', [
            'prodi' => $prodi,
            'peringkat' => $peringkat,
            'lembaga' => $lembaga
        ]);
        // return response()->json([
        //     'prodi' => $prodi,
        //     'peringkat' => $peringkat,
        //     'lembaga' => $lembaga
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'akreditasi_sk' => 'required|string|max:255',
            'akreditasi_tgl_awal' => 'required|date',
            'akreditasi_tgl_akhir' => 'required|date',
            'id_peringkat_akreditasi' => 'required',
            'akreditasi_status' => 'required|string|in:Berlaku,Dicabut,Tidak Berlaku',
            'id_lembaga_akreditasi' => 'required',
            'sk_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        Akreditasi::create([
            'akreditasi_sk' => $request->akreditasi_sk,
            'akreditasi_tgl_awal' => $request->akreditasi_tgl_awal,
            'akreditasi_tgl_akhir' => $request->akreditasi_tgl_akhir,
            'id_peringkat_akreditasi' => $request->id_peringkat_akreditasi,
            'akreditasi_status' => $request->akreditasi_status,
            'id_lembaga_akreditasi' => $request->id_lembaga_akreditasi,
            'akreditasi_dokumen' => $suratKeputusan ?? null,
            'id_prodi' => $request->id_prodi,
            'id_user' => Auth::user()->id,
        ]);

        return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_prodi])->with('success', 'Akreditasi Program Studi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
