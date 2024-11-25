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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = DB::table('program_studis')
            ->leftJoin('organisasis', 'program_studis.id_organization', '=', 'organisasis.id')
            ->leftJoin('akreditasis', function ($join) {
                $join->on('program_studis.id', '=', 'akreditasis.id_prodi')
                    ->whereRaw('akreditasis.akreditasi_tgl_akhir = (
                                SELECT MAX(sub.akreditasi_tgl_akhir)
                                FROM akreditasis as sub
                                WHERE sub.id_prodi = program_studis.id
            )');
            })
            ->leftJoin('lembaga_akreditasis', 'akreditasis.id_lembaga_akreditasi', '=', 'lembaga_akreditasis.id')
            ->leftJoin('peringkat_akreditasis', 'akreditasis.id_peringkat_akreditasi', '=', 'peringkat_akreditasis.id')
            ->orderBy('organisasis.organisasi_kode')
            ->orderBy('program_studis.prodi_kode')
            ->select(
                'program_studis.id as id',
                'organisasis.organisasi_kode as kode_pt',
                'organisasis.organisasi_nama as nama_pt',
                'program_studis.prodi_kode as prodi_kode',
                'program_studis.prodi_nama as prodi_nama',
                'program_studis.prodi_jenjang as prodi_jenjang',
                'akreditasis.akreditasi_sk as no_sk_akreditasi',
                'program_studis.prodi_periode as prodi_periode',
                'program_studis.prodi_active_status as status',
                'akreditasis.akreditasi_tgl_awal as tgl_terbit_sk_akreditasi',
                'peringkat_akreditasis.peringkat_nama as akreditasi',
                'akreditasis.akreditasi_tgl_akhir as tgl_akhir_sk_akreditasi',
            )
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
            'prodi_periode' => 'required|digits:5|integer|min:1900',
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
            'prodi_periode.digit' => 'Periode Pelaporan harus 5 digit',
            'prodi_periode.integer' => 'Periode harus berupa angka',
            'prodi_periode.min' => 'Periode minimal 1900',
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
            'prodi_periode' => 'required|digits:5|integer|min:1900',
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
            'prodi_periode.digit' => 'Periode Pelaporan harus 5 digit',
            'prodi_periode.integer' => 'Periode harus berupa angka',
            'prodi_periode.min' => 'Periode minimal 1900',
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
        $prodi = ProgramStudi::with([
            'historiPerguruanTinggi:id,id_prodi,prodi_kode,prodi_nama,prodi_jenjang,prodi_periode,prodi_active_status,sk_nomor,sk_tanggal',
            'suratKeputusan' => function ($query) {
                $query->select('id', 'sk_nomor', 'id_prodi', 'sk_tanggal', 'id_jenis_surat_keputusan', 'sk_dokumen')
                    ->whereHas('jenisSuratKeputusan', function ($subQuery) {
                        $subQuery->where('jsk_nama', 'SK Pendirian');
                    })
                    ->oldest('sk_tanggal')->limit(1);
            },
            'suratKeputusan.jenisSuratKeputusan:id,jsk_nama',
            'perguruanTinggi:id'
        ])->select(
            'id',
            'prodi_kode',
            'prodi_nama',
            'prodi_jenjang',
            'prodi_periode',
            'prodi_active_status',
            'id_organization'
        )->findOrFail($id);

        $prodi->suratKeputusan = $prodi->suratKeputusan->first();

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
            ->select('id', 'title', 'no_perkara', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        $sk = SuratKeputusan::where('id_prodi', $id)
            ->with('jenisSuratKeputusan:id,jsk_nama')
            ->select('id', 'sk_nomor', 'id_prodi', 'sk_tanggal', 'id_jenis_surat_keputusan', 'sk_dokumen')
            ->orderBy('sk_tanggal', 'asc')
            ->get();

        return view('Organisasi.ProgramStudi.Show', [
            'prodi' => $prodi,
            'akreditasis' => $akreditasis,
            'perkaras' => $perkaras,
            'sk' => $sk,
        ]);

        // return response()->json([
        //     'prodi' => $prodi,
        //     'akreditasis' => $akreditasis,
        //     'perkaras' => $perkaras,
        //     'sk' => $sk,
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
            $query->select('id', 'sk_nomor', 'id_prodi', 'sk_tanggal', 'id_jenis_surat_keputusan', 'sk_dokumen')
                ->whereHas('jenisSuratKeputusan', function ($subQuery) {
                    $subQuery->where('jsk_nama', 'SK Pendirian');
                })
                ->oldest('sk_tanggal')->limit(1);
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
            'id_organization' => 'required',
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode',
            'prodi_nama' => 'required',
            'prodi_jenjang' => 'required',
            'prodi_periode' => 'required|digits:5|integer|min:1900',
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
            'prodi_periode.digit' => 'Periode Pelaporan harus 5 digit',
            'prodi_periode.integer' => 'Periode harus berupa angka',
            'prodi_periode.min' => 'Periode minimal 1900',
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $request->validate([
           'id_organization' => 'required',
            'prodi_kode' => 'required|string|max:7|unique:program_studis,prodi_kode',
            'prodi_nama' => 'required',
            'prodi_jenjang' => 'required',
            'prodi_periode' => 'required|digits:5|integer|min:1900',
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
            'prodi_periode.digit' => 'Periode Pelaporan harus 5 digit',
            'prodi_periode.integer' => 'Periode harus berupa angka',
            'prodi_periode.min' => 'Periode minimal 1900',
            'prodi_active_status.required' => 'Status Aktif Program Studi harus diisi.',
            'sk_nomor.required' => 'Nomor SK harus diisi.',
            'sk_tanggal.required' => 'Tanggal SK harus diisi.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus diisi.',
            'sk_dokumen.required' => 'Dokumen SK harus diisi.',
            'sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX.',
            'sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2MB.',
        ]);

        $prodi->update([
            'prodi_kode' => $request->prodi_kode,
            'prodi_nama' => $request->prodi_nama,
            'prodi_active_status' => $request->prodi_active_status,
            'prodi_jenjang' => $request->prodi_jenjang,
            'prodi_periode' => $request->prodi_periode,
        ]);

        $sk = SuratKeputusan::updateOrCreate(
            ['id_prodi' => $prodi->id, 'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan],
            [
                'sk_nomor' => $request->sk_nomor,
                'sk_tanggal' => $request->sk_tanggal,
                'sk_dokumen' => $request->file('sk_dokumen') ? $request->file('sk_dokumen')->store('sk_dokumen') : null,
            ]
        );

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
