<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Organisasi;
use App\Models\PimpinanOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PimpinanPerguruanTinggiController extends Controller
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
        // dd($id);
        $pt = Organisasi::findOrFail($id);
        $jabatan = Jabatan::select('id', 'jabatan_nama', 'jabatan_status', 'jabatan_organisasi')
            ->where('bentuk_pt', $pt->organisasi_bentuk_pt)->get();

        return view('Pimpinan.PerguruanTinggi.Create', [
            'pt' => $pt,
            'jabatan' => $jabatan
        ]);

        // return response()->json([
        //     'pt' => $pt,
        //     'jabatan' => $jabatan
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pimpinan_nama' => 'required|string|max:255',
            'pimpinan_email' => 'required|email|max:255',
            'pimpinan_sk' => 'required|string|max:255',
            'pimpinan_tanggal' => 'required|date',
            'pimpinan_tanggal_berakhir' => 'required|date',
            'id_jabatan' => 'required|exists:jabatans,id',
            'pimpinan_sk_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('pimpinan_sk_dokumen')) {
            $filePath = $request->file('pimpinan_sk_dokumen')->store('dokumen_sk', 'public');
        }

        PimpinanOrganisasi::create([
            'id_organization' => $request->id_organization,
            'pimpinan_nama' => $request->pimpinan_nama,
            'pimpinan_email' => $request->pimpinan_email,
            'pimpinan_sk' => $request->pimpinan_sk,
            'pimpinan_tanggal' => $request->pimpinan_tanggal,
            'pimpinan_tanggal_berakhir' => $request->pimpinan_tanggal_berakhir,
            'id_jabatan' => $request->id_jabatan,
            'pimpinan_sk_dokumen' => $filePath,
            'pimpinan_status' => 'Berlaku',
        ]);

        return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_organization])->with('success', 'Data pimpinan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pimpinan = DB::table('pimpinan_organisasis')
            ->where('pimpinan_organisasis.id', $id)
            ->join('jabatans', 'pimpinan_organisasis.id_jabatan', '=', 'jabatans.id')
            ->select('pimpinan_organisasis.*', 'jabatans.jabatan_nama')
            ->first();

        return response()->json($pimpinan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pimpinan = PimpinanOrganisasi::findOrFail($id);
        $jabatan = Jabatan::select('id', 'jabatan_nama')
            ->where('bentuk_pt', $pimpinan->organization->organisasi_bentuk_pt)
            ->orderBy('jabatan_nama', 'asc')
            ->get();

        return view('Pimpinan.PerguruanTinggi.Edit', [
            'pimpinan' => $pimpinan,
            'jabatan' => $jabatan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'pimpinan_nama' => 'required|string|max:255',
            'pimpinan_email' => 'required|email|max:255',
            'pimpinan_sk' => 'required|string|max:255',
            'pimpinan_tanggal' => 'required|date',
            'pimpinan_tanggal_berakhir' => 'required|date',
            'pimpinan_status' => 'required|string|max:255',
            'id_jabatan' => 'required|exists:jabatans,id',
            'pimpinan_sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('pimpinan_sk_dokumen')) {
            $filePath = $request->file('pimpinan_sk_dokumen')->store('dokumen_sk', 'public');
            PimpinanOrganisasi::where('id', $id)->update([
                'id_organization' => $request->id_organization,
                'pimpinan_nama' => $request->pimpinan_nama,
                'pimpinan_email' => $request->pimpinan_email,
                'pimpinan_sk' => $request->pimpinan_sk,
                'pimpinan_tanggal' => $request->pimpinan_tanggal,
                'pimpinan_tanggal_berakhir' => $request->pimpinan_tanggal_berakhir,
                'id_jabatan' => $request->id_jabatan,
                'pimpinan_sk_dokumen' => $filePath,
                'pimpinan_status' => $request->pimpinan_status,
            ]);
        } else {
            PimpinanOrganisasi::where('id', $id)->update([
                'id_organization' => $request->id_organization,
                'pimpinan_nama' => $request->pimpinan_nama,
                'pimpinan_email' => $request->pimpinan_email,
                'pimpinan_sk' => $request->pimpinan_sk,
                'pimpinan_tanggal' => $request->pimpinan_tanggal,
                'pimpinan_tanggal_berakhir' => $request->pimpinan_tanggal_berakhir,
                'id_jabatan' => $request->id_jabatan,
                'pimpinan_status' => $request->pimpinan_status,
            ]);
        }

        return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_organization])->with('success', 'Data pimpinan berhasil ditambahkan.');
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
        if (!$request->pimpinan_sk_dokumen) {
            return abort(404);
        }
        return response()->file(storage_path('app/public/' . $request->pimpinan_sk_dokumen));
    }
}
