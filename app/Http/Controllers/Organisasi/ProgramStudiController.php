<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Models\Akreditasi;
use App\Models\HistoryProgramStudi;
use App\Models\JenisSuratKeputusan;
use App\Models\Organisasi;
use App\Models\ProgramStudi;
use App\Models\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProgramStudiController extends Controller
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
        $organisasi = Organisasi::select(
            'id',
            'organisasi_nama'
        )->findOrFail($id);

        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        return view('Organisasi.ProgramStudi.Create', [
            'organisasi' => $organisasi,
            'jenis' => $jenis
        ]);
        // return response()->json(['organisasi' => $organisasi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'id_organization' => 'required',
            'prodi_nama' => 'required',
            'prodi_jenjang' => 'required',
            'prodi_active_status' => 'required',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'id_jenis_surat_keputusan' => 'required',
            'sk_dokumen' => 'required',
        ]);

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        $prodi = ProgramStudi::create([
            'id' => Str::uuid(),
            'id_organization' => $validated['id_organization'],
            'prodi_nama' => $validated['prodi_nama'],
            'prodi_jenjang' => $validated['prodi_jenjang'],
            'prodi_active_status' => $validated['prodi_active_status'],
            'id_user' => Auth::user()->id
        ]);

        SuratKeputusan::create([
            'sk_nomor' => $validated['sk_nomor'],
            'sk_tanggal' => $validated['sk_tanggal'],
            'sk_dokumen' => $suratKeputusan,
            'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
            'id_prodi' => $prodi->id,
        ]);

        HistoryProgramStudi::create([
            'id' => Str::uuid(),
            'id_prodi' => $prodi->id,
            'prodi_nama' => $validated['prodi_nama'],
            'prodi_jenjang' => $validated['prodi_jenjang'],
            'prodi_active_status' => $validated['prodi_active_status'],
            'sk_nomor' => $validated['sk_nomor'],
            'sk_tanggal' => $validated['sk_tanggal'],
            'sk_dokumen' => $suratKeputusan,
            'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
            'id_user' => Auth::user()->id
        ]);

        return redirect()->route('perguruan-tinggi.show', $validated['id_organization'])->with('success', 'Program Studi berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prodi = ProgramStudi::select(
            'id',
            'prodi_nama',
            'prodi_jenjang',
            'prodi_active_status',
            'id_organization',
        )->with([
            'historiPerguruanTinggi' => function ($query) {
                $query->select('id', 'id_prodi', 'prodi_nama', 'prodi_jenjang', 'prodi_active_status', 'sk_nomor', 'sk_tanggal')
                    ->orderBy('created_at', 'asc');
            }
        ])->with(['perguruanTinggi' => function ($query) {
            $query->select('id');
        }])->findOrFail($id);

        $akreditasis = Akreditasi::where('id_prodi', $id)
            ->select(
                'id_prodi',
                'akreditasi_sk',
                'akreditasi_tgl_awal',
                'akreditasi_tgl_akhir',
                'akreditasi_status',
                'aktif'
            )->with(['prodi:id,prodi_nama,prodi_jenjang'])->orderBy('created_at', 'asc')->get();

        return view('Organisasi.ProgramStudi.Show', [
            'prodi' => $prodi,
            'akreditasis' => $akreditasis
        ]);
        // return response()->json([
        //     'prodi' => $prodi,
        //     'akreditasis' => $akreditasis
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // dd($id);
        $prodi = ProgramStudi::select(
            'id',
            'prodi_nama',
            'prodi_jenjang',
            'prodi_active_status'
        )->with(['suratKeputusan' => function ($query) {
            $query->orderBy('created_at', 'desc')->first();
        }])->findOrFail($id);

        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        return view('Organisasi.ProgramStudi.Edit', [
            'prodi' => $prodi,
            'jenis' => $jenis,
        ]);
        // return response()->json(['prodi' => $prodi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $request->validate([
            'prodi_nama' => 'required|string|max:255',
            'prodi_active_status' => 'required|string',
            'prodi_jenjang' => 'required|string',
            'sk_nomor' => 'required|string|max:255',
            'sk_tanggal' => 'required|date',
            'sk_dokumen' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'id_jenis_surat_keputusan' => 'required'
        ]);

        $prodi->update([
            'prodi_nama' => $request->prodi_nama,
            'prodi_active_status' => $request->prodi_active_status,
            'prodi_jenjang' => $request->prodi_jenjang,
        ]);

        if ($request->hasFile('sk_dokumen')) {
            $skFilePath = $request->file('sk_dokumen')->store('dokumen/sk', 'public');
        } else {
            $skFilePath = $request->input('existing_sk_dokumen');
        }

        SuratKeputusan::create([
            'sk_nomor' => $request->sk_nomor,
            'sk_tanggal' => $request->sk_tanggal,
            'sk_dokumen' => $skFilePath,
            'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
            'id_prodi' => $prodi->id,
        ]);

        HistoryProgramStudi::create([
            'id' => Str::uuid(),
            'id_prodi' => $prodi->id,
            'prodi_nama' => $request->prodi_nama,
            'prodi_active_status' => $request->prodi_active_status,
            'prodi_jenjang' => $request->prodi_jenjang,
            'sk_nomor' => $request->sk_nomor,
            'sk_tanggal' => $request->sk_tanggal,
            'sk_dokumen' => $skFilePath,
            'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
            'id_user' => Auth::user()->id,
        ]);

        return back()->with('success', 'Data Sudah Terekam');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
