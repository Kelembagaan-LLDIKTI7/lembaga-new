<?php

namespace App\Http\Controllers\Organisasi;

use App\Exports\ProdiExport;
use App\Http\Controllers\Controller;
use App\Models\Akreditasi;
use App\Models\HistoryProgramStudi;
use App\Models\JenisSuratKeputusan;
use App\Models\Organisasi;
use App\Models\Perkara;
use App\Models\ProgramStudi;
use App\Models\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = ProgramStudi::select('id', 'id_organization', 'prodi_kode', 'prodi_nama', 'prodi_jenjang', 'prodi_periode', 'prodi_active_status')
            ->with(['akreditasis' => function ($query) {
                $query->select('id', 'akreditasi_sk', 'id_prodi', 'id_peringkat_akreditasi', 'akreditasi_tgl_akhir')
                    ->orderBy('created_at', 'desc')
                    ->limit(1);
            }, 'akreditasis.peringkat_akreditasi' => function ($query) {
                $query->select('id', 'peringkat_nama', 'peringkat_logo');
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        // return response()->json(['prodis' => $prodis]);
        return view('Organisasi.ProgramStudi.IndexAdmin', ['prodis' => $prodis]);
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

    public function validationStore(Request $request)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'id_organization' => 'required',
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode',
            'prodi_nama' => 'required',
            'prodi_jenjang' => 'required',
            'prodi_periode' => 'required',
            'prodi_active_status' => 'required',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'id_jenis_surat_keputusan' => 'required',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'prodi_kode.required' => 'Kode Program Studi harus diisi.',
            'prodi_kode.max' => 'Kode Program Studi tidak boleh lebih dari 7 karakter.',
            'prodi_kode.unique' => 'Kode Program Studi sudah digunakan.',
            'prodi_nama.required' => 'Nama Program Studi harus diisi.',
            'prodi_periode.required' => 'Periode Pelaporan Program Studi harus diisi',
            'prodi_jenjang.required' => 'Jenjang Program Studi harus diisi.',
            'prodi_active_status.required' => 'Status Aktif Program Studi harus diisi.',
            'sk_nomor.required' => 'Nomor SK harus diisi.',
            'sk_tanggal.required' => 'Tanggal SK harus diisi.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus diisi.',
            'sk_dokumen.required' => 'Dokumen SK harus diisi.',
            'sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX.',
            'sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2MB.',
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'id_organization' => 'required',
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode',
            'prodi_nama' => 'required',
            'prodi_jenjang' => 'required',
            'prodi_periode' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'prodi_active_status' => 'required',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'id_jenis_surat_keputusan' => 'required',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'prodi_kode.required' => 'Kode Program Studi harus diisi.',
            'prodi_kode.max' => 'Kode Program Studi tidak boleh lebih dari 7 karakter.',
            'prodi_kode.unique' => 'Kode Program Studi sudah digunakan.',
            'prodi_nama.required' => 'Nama Program Studi harus diisi.',
            'prodi_jenjang.required' => 'Jenjang Program Studi harus diisi.',
            'prodi_periode.required' => 'Periode Pelaporan Program Studi harus diisi',
            'prodi_periode.digits' => 'Periode Pelaporan Program Studi harus memilliki 4 digit',
            'prodi_periode.min' => 'Periode Pelaporan Program Studi minimal tahun 1900',
            'prodi_active_status.required' => 'Status Aktif Program Studi harus diisi.',
            'sk_nomor.required' => 'Nomor SK harus diisi.',
            'sk_tanggal.required' => 'Tanggal SK harus diisi.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus diisi.',
            'sk_dokumen.required' => 'Dokumen SK harus diisi.',
            'sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX.',
            'sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2MB.',
        ]);

        $prodi = ProgramStudi::create([
            'id' => Str::uuid(),
            'id_organization' => $validated['id_organization'],
            'prodi_kode' => $validated['prodi_kode'],
            'prodi_nama' => $validated['prodi_nama'],
            'prodi_jenjang' => $validated['prodi_jenjang'],
            'prodi_periode' => $validated['prodi_periode'],
            'prodi_active_status' => $validated['prodi_active_status'],
            'id_user' => Auth::user()->id
        ]);

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
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
                'prodi_kode' => $request->prodi_kode,
                'prodi_nama' => $validated['prodi_nama'],
                'prodi_jenjang' => $validated['prodi_jenjang'],
                'prodi_periode' => $validated['prodi_periode'],
                'prodi_active_status' => $validated['prodi_active_status'],
                'sk_nomor' => $validated['sk_nomor'],
                'sk_tanggal' => $validated['sk_tanggal'],
                'sk_dokumen' => $suratKeputusan,
                'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
                'id_user' => Auth::user()->id
            ]);
        } else {
            SuratKeputusan::create([
                'sk_nomor' => $validated['sk_nomor'],
                'sk_tanggal' => $validated['sk_tanggal'],
                'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
                'id_prodi' => $prodi->id,
            ]);

            HistoryProgramStudi::create([
                'id' => Str::uuid(),
                'id_prodi' => $prodi->id,
                'prodi_kode' => $request->prodi_kode,
                'prodi_nama' => $validated['prodi_nama'],
                'prodi_jenjang' => $validated['prodi_jenjang'],
                'prodi_periode' => $validated['prodi_periode'],
                'prodi_active_status' => $validated['prodi_active_status'],
                'sk_nomor' => $validated['sk_nomor'],
                'sk_tanggal' => $validated['sk_tanggal'],
                'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
                'id_user' => Auth::user()->id
            ]);
        }

        session()->flash('success', 'Program Studi berhasil ditambah.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('perguruan-tinggi.show', $validated['id_organization'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prodi = ProgramStudi::select(
            'id',
            'prodi_kode',
            'prodi_nama',
            'prodi_jenjang',
            'prodi_periode',
            'prodi_active_status',
            'id_organization',
        )->with([
            'historiPerguruanTinggi' => function ($query) {
                $query->select('id', 'id_prodi', 'prodi_kode', 'prodi_nama', 'prodi_jenjang', 'prodi_periode', 'prodi_active_status', 'sk_nomor', 'sk_tanggal')
                    ->orderBy('created_at', 'asc');
            }
        ])->with(['perguruanTinggi' => function ($query) {
            $query->select('id');
        }])->findOrFail($id);

        $akreditasis = Akreditasi::where('id_prodi', $id)
            ->select(
                'id',
                'id_prodi',
                'akreditasi_sk',
                'akreditasi_tgl_awal',
                'akreditasi_tgl_akhir',
                'akreditasi_status',
                'aktif',
                'id_peringkat_akreditasi'
            )->with(['prodi:id,prodi_kode,prodi_nama,prodi_jenjang', 'peringkat_akreditasi:id,peringkat_nama'])->orderBy('created_at', 'asc')->get();

        $perkaras = Perkara::where('id_prodi', $id)
            ->select('id', 'title', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        $sk = SuratKeputusan::where('id_prodi', $id)
            ->first();

        return view('Organisasi.ProgramStudi.Show', [
            'prodi' => $prodi,
            'akreditasis' => $akreditasis,
            'perkaras' => $perkaras,
            'sk' => $sk,
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
            'prodi_kode',
            'prodi_nama',
            'prodi_jenjang',
            'prodi_periode',
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

    public function validationUpdate(Request $request, string $id)
    {
        // Validasi data input
        $prodi = ProgramStudi::findOrFail($id);
        $validator = \Validator::make($request->all(), [
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode,' . $prodi->id,
            'prodi_nama' => 'required|string|max:255',
            'prodi_active_status' => 'required|string',
            'prodi_periode' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'prodi_jenjang' => 'required|string',
        ], [
            'prodi_kode.required' => 'Kode Program Studi harus diisi.',
            'prodi_kode.max' => 'Kode Program Studi harus terdiri dari 6 karakter.',
            'prodi_kode.unique' => 'Kode Program Studi sudah terdaftar.',
            'prodi_nama.required' => 'Nama Program Studi harus diisi.',
            'prodi_nama.max' => 'Nama Program Studi tidak boleh lebih dari 255 karakter.',
            'prodi_active_status.required' => 'Status Program Studi harus diisi.',
            'prodi_jenjang.required' => 'Jenjang Program Studi harus diisi.',
            'prodi_periode.required' => 'Periode Pelaporan Program Studi harus diisi',
            'prodi_periode.digits' => 'Periode Pelaporan Program Studi harus memilliki 4 digit',
            'prodi_periode.min' => 'Periode Pelaporan Program Studi minimal tahun 1900',
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $request->validate([
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode,' . $prodi->id,
            'prodi_nama' => 'required|string|max:255',
            'prodi_active_status' => 'required|string',
            'prodi_jenjang' => 'required|string',
            'prodi_periode' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
        ], [
            'prodi_kode.required' => 'Kode Program Studi harus diisi.',
            'prodi_kode.max' => 'Kode Program Studi harus terdiri dari 6 karakter.',
            'prodi_kode.unique' => 'Kode Program Studi sudah terdaftar.',
            'prodi_nama.required' => 'Nama Program Studi harus diisi.',
            'prodi_nama.max' => 'Nama Program Studi tidak boleh lebih dari 255 karakter.',
            'prodi_active_status.required' => 'Status Program Studi harus diisi.',
            'prodi_jenjang.required' => 'Jenjang Program Studi harus diisi.',
            'prodi_periode.required' => 'Periode Pelaporan Program Studi harus diisi',
            'prodi_periode.digits' => 'Periode Pelaporan Program Studi harus memilliki 4 digit',
            'prodi_periode.min' => 'Periode Pelaporan Program Studi minimal tahun 1900',
        ]);

        $prodi->update([
            'prodi_kode' => $request->prodi_kode,
            'prodi_nama' => $request->prodi_nama,
            'prodi_active_status' => $request->prodi_active_status,
            'prodi_jenjang' => $request->prodi_jenjang,
            'prodi_periode' => $request->prodi_periode,
        ]);

        HistoryProgramStudi::create([
            'id' => Str::uuid(),
            'id_prodi' => $prodi->id,
            'prodi_kode' => $request->prodi_kode,
            'prodi_nama' => $request->prodi_nama,
            'prodi_active_status' => $request->prodi_active_status,
            'prodi_jenjang' => $request->prodi_jenjang,
            'prodi_periode' => $request->prodi_periode,
            'id_user' => Auth::user()->id,
        ]);

        session()->flash('success', 'Program Studi berhasil diubah.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('program-studi.show', $prodi->id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export()
    {
        $filename = 'program_studi_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new ProdiExport, $filename);
    }
}
