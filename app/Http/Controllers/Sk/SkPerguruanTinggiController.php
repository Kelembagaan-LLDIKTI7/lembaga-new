<?php

namespace App\Http\Controllers\Sk;

use App\Http\Controllers\Controller;
use App\Models\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkPerguruanTinggiController extends Controller
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
        $jenis = DB::table('jenis_surat_keputusans')->get();
        $id_organization = $id;

        return view('SK.PerguruanTinggi.Create', [
            'jenis' => $jenis,
            'id_organization' => $id_organization
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'sk_nomor' => 'required|string|max:255',
            'sk_tanggal' => 'required|date',
            'id_jenis_surat_keputusan' => 'required|exists:jenis_surat_keputusans,id',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Maksimal 2MB
        ]);

        // Menyimpan dokumen SK
        if ($request->hasFile('sk_dokumen')) {
            $filePath = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        SuratKeputusan::create([
            'sk_nomor' => $request->sk_nomor,
            'sk_tanggal' => $request->sk_tanggal,
            'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
            'sk_dokumen' => $filePath,
            'id_organization' => $request->id_organization,
        ]);

        return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_organization])->with('success', 'Pimpinan Perguruan Tinggi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id', $id)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.*', 'jenis_surat_keputusans.jsk_nama')
            ->first();

        return response()->json($sk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenis = DB::table('jenis_surat_keputusans')->get();
        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id', $id)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.*', 'jenis_surat_keputusans.id as jsk_id', 'jenis_surat_keputusans.jsk_nama')
            ->first();

        return view('SK.PerguruanTinggi.Edit', [
            'sk' => $sk,
            'jenis' => $jenis
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sk = SuratKeputusan::findOrFail($id);

        $request->validate([
            'sk_nomor' => 'required|string|max:255',
            'sk_tanggal' => 'required|date',
            'id_jenis_surat_keputusan' => 'required|exists:jenis_surat_keputusans,id',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Maksimal 2MB
        ]);

        // Menyimpan dokumen SK
        if ($request->hasFile('sk_dokumen')) {
            $filePath = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
            $sk->update([
                'sk_nomor' => $request->sk_nomor,
                'sk_tanggal' => $request->sk_tanggal,
                'sk_dokumen' => $filePath,
                'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                'id_organization' => $request->id_organization,
            ]);
        } else {
            $sk->update([
                'sk_nomor' => $request->sk_nomor,
                'sk_tanggal' => $request->sk_tanggal,
                'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                'id_organization' => $request->id_organization,
            ]);
        }

        return redirect()->route('perguruan-tinggi.show', ['id' => $sk->id_organization])->with('success', 'SK Perguruan Tinggi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function viewPdf(Request $request)
    {
        if (!$request->sk_dokumen) {
            return abort(404);
        }

        return response()->file(storage_path('app/public/' . $request->sk_dokumen));
    }
}
