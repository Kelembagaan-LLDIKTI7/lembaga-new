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

    public function validationStore(Request $request)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'akreditasi_sk' => 'required|string|max:255',
            'akreditasi_tgl_awal' => 'required|date',
            'akreditasi_tgl_akhir' => 'required|date',
            'id_peringkat_akreditasi' => 'required',
            'id_organization' => 'required',
            'akreditasi_status' => 'required|string|in:Berlaku,Dicabut,Tidak Berlaku',
            'id_lembaga_akreditasi' => 'required',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'akreditasi_sk.required' => 'Nomor harus diisi.',
            'akreditasi_sk.max' => 'Nomor tidak boleh lebih dari 255 karakter.',
            'akreditasi_tgl_awal.required' => 'Tanggal harus diisi.',
            'akreditasi_tgl_awal.date' => 'Tanggal harus valid.',
            'akreditasi_tgl_akhir.required' => 'Tanggal harus diisi.',
            'akreditasi_tgl_akhir.date' => 'Tanggal harus valid.',
            'id_peringkat_akreditasi.required' => 'Peringkat harus dipilih.',
            'akreditasi_status.required' => 'Status harus dipilih.',
            'akreditasi_status.in' => 'Status harus dipilih.',
            'id_lembaga_akreditasi.required' => 'Lembaga harus dipilih.',
            'sk_dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, atau DOCX.',
            'sk_dokumen.max' => 'Dokumen tidak boleh lebih dari 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
    public function create($id)
    {
        $prodi = ProgramStudi::select('id', 'id_organization')
            ->with(['perguruanTinggi' => function ($query) {
                $query->select('id');
            }])
            ->findOrFail($id);
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
            'id_organization' => 'required',
            'akreditasi_status' => 'required|string|in:Berlaku,Dicabut,Tidak Berlaku',
            'id_lembaga_akreditasi' => 'required',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $updateAkreditasi = Akreditasi::where('id_prodi', $request->id_prodi)->where('aktif', 'Ya')->first();
        // dd($updateAkreditasi);
        if ($updateAkreditasi) {
            $updateAkreditasi->update(['aktif' => 'Tidak']);
        }

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
            'id_organization' => $request->id_organization,
            'aktif' => 'Ya',
            'id_user' => Auth::user()->id,
        ]);

        session()->flash('success', 'Akreditasi Program Studi berhasil ditambahkan');

        return response()->json([
            'success' => true,
            'redirect_url' => route('program-studi.show', $request->id_prodi)
        ]);
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
