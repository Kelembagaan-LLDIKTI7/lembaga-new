<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Akta;
use App\Models\Kota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AktaBpController extends Controller
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
        $bp = Organisasi::select('id')->findOrFail($id);
        $kota =  Kota::select(
            'id',
            'nama'
        )->get();
        $akta = Akta::select('id', 'akta_nomor')->get();
        return view('Dokumen.AktaBp.Create', [
            'bp' => $bp,
            'kota' => $kota,
            'akta' => $akta
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_organization' => 'required',
            'akta_nomor' => 'required|string|max:255',
            'akta_tanggal' => 'required|date',
            'akta_nama_notaris' => 'required|string|max:255',
            'akta_jenis' => 'required',
            'kotaAkta' => 'required|string',
            'akta_keterangan' => 'nullable|string',
            'aktaDokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('aktaDokumen')) {
            $akta = $request->file('aktaDokumen')->store('akta', 'public');
        }

        Akta::create([
            'akta_nomor' => $request->akta_nomor,
            'akta_tanggal' => $request->akta_tanggal,
            'akta_nama_notaris' => $request->akta_nama_notaris,
            'akta_kota_notaris' => $request->kotaAkta,
            'id_organization' => $request->id_organization,
            'akta_jenis' => $request->akta_jenis,
            'akta_dokumen' => $akta,
            'akta_keterangan' => $request->akta_keterangan,
            'id_user' => Auth::user()->id,
        ]);

        return redirect()->route('badan-penyelenggara.show', ['id' => $request->id_organization])->with('success', 'Data akta berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $akta = Akta::where('id', $id)->with('skKumham')->first();
        $akta = DB::table('aktas')
            ->where('aktas.id', $id)
            ->leftJoin('sk_kumhams', 'aktas.id', '=', 'sk_kumhams.id_akta')
            ->select('aktas.*', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_tanggal', 'sk_kumhams.kumham_perihal', 'sk_kumhams.kumham_dokumen')
            ->first();

        return response()->json($akta);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $akta = DB::table('aktas')
            ->where('aktas.id', $id)
            ->first();

        $aktas  = DB::table('aktas')->get();

        $kota =  Kota::select(
            'id',
            'nama'
        )->get();

        return view('Dokumen.AktaBp.Edit', [
            'akta' => $akta,
            'aktas' => $aktas,
            'kota' => $kota,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_organization' => 'required',
            'akta_nomor' => 'required|string|max:255',
            'akta_tanggal' => 'required|date',
            'akta_nama_notaris' => 'required|string|max:255',
            'akta_jenis' => 'required',
            'kotaAkta' => 'required|string',
            'akta_keterangan' => 'nullable|string',
            'aktaDokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('aktaDokumen')) {
            $akta = $request->file('aktaDokumen')->store('akta', 'public');

            Akta::where('id', $id)->update([
                'akta_nomor' => $request->akta_nomor,
                'akta_tanggal' => $request->akta_tanggal,
                'akta_nama_notaris' => $request->akta_nama_notaris,
                'akta_kota_notaris' => $request->kotaAkta,
                'id_organization' => $request->id_organization,
                'akta_jenis' => $request->akta_jenis,
                'akta_keterangan' => $request->akta_keterangan,
                'akta_dokumen' => $akta,
                'id_user' => Auth::user()->id,
            ]);
        } else {
            Akta::where('id', $id)->update([
                'akta_nomor' => $request->akta_nomor,
                'akta_tanggal' => $request->akta_tanggal,
                'akta_nama_notaris' => $request->akta_nama_notaris,
                'akta_kota_notaris' => $request->kotaAkta,
                'id_organization' => $request->id_organization,
                'akta_jenis' => $request->akta_jenis,
                'akta_keterangan' => $request->akta_keterangan,
                'id_user' => Auth::user()->id,
            ]);
        }

        return redirect()->route('badan-penyelenggara.show', ['id' => $request->id_organization])->with('success', 'Data akta berhasil ditambahkan.');
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
        if (!$request->akta_dokumen) {
            return abort(404);
        }
        return response()->file(storage_path('app/public/' . $request->akta_dokumen));
    }
}
