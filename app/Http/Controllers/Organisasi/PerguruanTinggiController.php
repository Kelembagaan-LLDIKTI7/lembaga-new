<?php

namespace App\Http\Controllers\Organisasi;

use App\Exports\PtExport;
use App\Http\Controllers\Controller;
use App\Imports\PtImport;
use App\Models\Akreditasi;
use App\Models\BentukPt;
use App\Models\HistoriPt;
use App\Models\JenisSuratKeputusan;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\Perkara;
use App\Models\PimpinanOrganisasi;
use App\Models\SuratKeputusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\select;

class PerguruanTinggiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Organisasi::where('organisasi_type_id', 3)
            ->where(function ($q) {
                $q->whereNull('tampil')
                    ->orWhereNot('tampil', 0);
            })
            ->select(
                'id',
                'organisasi_kode',
                'organisasi_nama as pt_nama',
                'organisasi_nama_singkat',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
                'parent_id'
            )
            ->with([
                'parent:id,organisasi_nama',
                'akreditasi_terakhir_pt' => function ($query) {
                    $query->with('peringkat_akreditasi:id,peringkat_nama');
                }
            ])
            ->orderBy('organisasi_kode', 'asc');

        if ($user->hasRole('Perguruan Tinggi')) {
            $query->where('id', $user->id_organization);

            $perguruanTinggis = $query->get();

            return redirect()->route('perguruan-tinggi.show', ['id' => $perguruanTinggis->first()->id]);
        } elseif ($user->hasRole('Badan Penyelenggara')) {
            $query->where('parent_id', $user->id_organization);
        }

        $perguruanTinggis = $query->get();

        return view('Organisasi.PerguruanTinggi.Index', [
            'perguruanTinggis' => $perguruanTinggis
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        $kotas =  Kota::select(
            'id',
            'nama'
        )->get();

        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            // ->where('id', $user->id_organization)
            ->select('id', 'organisasi_nama')
            ->get();

        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        $bentukPt = BentukPt::all();

        return view('Organisasi.PerguruanTinggi.Create', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'perguruanTinggis' => $perguruanTinggis,
            'kotas' => $kotas,
            'jenis' => $jenis,
            'bentukPt' => $bentukPt
        ]);

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     'perguruanTinggis' => $perguruanTinggis,
        //     'kotas' => $kotas
        // ]);
    }

    public function validationStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'organisasi_kode' => 'required|string|size:6|unique:organisasis',
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|string|max:255',
            'organisasi_telp' => 'required|string|max:255',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|url|max:255',
            'organisasi_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organisasi_bentuk_pt' => 'required|uuid|exists:bentuk_pts,id',
            'parent_id' => 'nullable',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'sk_dokumen' => 'nullable',
            'id_jenis_surat_keputusan' => 'required',
            'berubah' => 'required',
            'organisasi_berubah_id' => 'nullable|array',
        ], [
            'organisasi_kode.required' => 'Kode Perguruan Tinggi harus diisi.',
            'organisasi_kode.size' => 'Kode Perguruan Tinggi harus terdiri dari 6 karakter.',
            'organisasi_kode.unique' => 'Kode Perguruan Tinggi sudah terdaftar.',
            'organisasi_nama.required' => 'Nama Perguruan Tinggi harus diisi.',
            'organisasi_email.required' => 'Email Perguruan Tinggi harus diisi.',
            'organisasi_email.email' => 'Format email tidak valid.',
            'organisasi_telp.required' => 'Nomor Telepon Perguruan Tinggi harus diisi.',
            'organisasi_kota.required' => 'Kota Perguruan Tinggi harus diisi.',
            'organisasi_alamat.required' => 'Alamat Perguruan Tinggi harus diisi.',
            'organisasi_logo.image' => 'Logo Perguruan Tinggi harus berupa gambar.',
            'organisasi_logo.mimes' => 'Logo Perguruan Tinggi harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Logo Perguruan Tinggi tidak boleh lebih dari 2MB.',
            'organisasi_bentuk_pt.required' => 'Bentuk Perguruan Tinggi harus diisi.',
            'organisasi_bentuk_pt.exists' => 'Bentuk Perguruan Tinggi tidak valid.',
            'sk_nomor.required' => 'Nomor Surat Keputusan harus diisi.',
            'sk_tanggal.required' => 'Tanggal Surat Keputusan harus diisi.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus diisi.',
            'berubah.required' => 'Jenis Surat Keputusan harus diisi.',
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
            'organisasi_kode' => 'required|string|size:6|unique:organisasis',
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|string|max:255',
            'organisasi_telp' => 'required|string|max:255',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|url|max:255',
            'organisasi_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organisasi_bentuk_pt' => 'required|uuid|exists:bentuk_pts,id',
            'parent_id' => 'nullable',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'sk_dokumen' => 'nullable',
            'id_jenis_surat_keputusan' => 'required',
            'berubah' => 'required',
            'organisasi_berubah_id' => 'nullable|array',
        ], [
            'organisasi_kode.required' => 'Kode Perguruan Tinggi harus diisi.',
            'organisasi_kode.size' => 'Kode Perguruan Tinggi harus terdiri dari 6 karakter.',
            'organisasi_kode.unique' => 'Kode Perguruan Tinggi sudah terdaftar.',
            'organisasi_nama.required' => 'Nama Perguruan Tinggi harus diisi.',
            'organisasi_email.required' => 'Email Perguruan Tinggi harus diisi.',
            'organisasi_email.email' => 'Format email tidak valid.',
            'organisasi_telp.required' => 'Nomor Telepon Perguruan Tinggi harus diisi.',
            'organisasi_kota.required' => 'Kota Perguruan Tinggi harus diisi.',
            'organisasi_alamat.required' => 'Alamat Perguruan Tinggi harus diisi.',
            'organisasi_logo.image' => 'Logo Perguruan Tinggi harus berupa gambar.',
            'organisasi_logo.mimes' => 'Logo Perguruan Tinggi harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Logo Perguruan Tinggi tidak boleh lebih dari 2MB.',
            'organisasi_bentuk_pt.required' => 'Bentuk Perguruan Tinggi harus diisi.',
            'organisasi_bentuk_pt.exists' => 'Bentuk Perguruan Tinggi tidak valid.',
            'sk_nomor.required' => 'Nomor Surat Keputusan harus diisi.',
            'sk_tanggal.required' => 'Tanggal Surat Keputusan harus diisi.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus diisi.',
            'berubah.required' => 'Jenis Surat Keputusan harus diisi.',
        ]);

        if ($request->hasFile('organisasi_logo')) {
            $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
        }

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        $organisasiBerubahId = $request->input('organisasi_berubah_id');

        $perguruanTinggi = Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_kode' => $validated['organisasi_kode'],
            'organisasi_nama' => $validated['organisasi_nama'],
            'organisasi_email' => $validated['organisasi_email'],
            'organisasi_telp' => $validated['organisasi_telp'],
            'organisasi_kota' => $validated['organisasi_kota'],
            'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
            'organisasi_website' => $validated['organisasi_website'] ?? null,
            'organisasi_alamat' => $validated['organisasi_alamat'],
            'organisasi_logo' => $logoPath ?? null,
            'organisasi_type_id' => 3,
            'organisasi_status' => 'Aktif',
            'organisasi_berubah_id' => !empty($organisasiBerubahId) ? json_encode($organisasiBerubahId) : null,
            'organisasi_bentuk_pt' => $validated['organisasi_bentuk_pt'],
            'parent_id' => $validated['parent_id'],
        ]);

        $status = '';
        if ($validated['berubah'] == 'Alih Bentuk') {
            $status = 'Perubahan Bentuk';
        } elseif ($validated['berubah'] == 'Penggabungan') {
            $status = 'Penggabungan';
        }

        if ($organisasiBerubahId) {
            foreach ($organisasiBerubahId as $id) {
                Organisasi::where('id', $id)->update([
                    'organisasi_status' => 'Alih Bentuk',
                    'organisasi_berubah_status' => $status,
                ]);
            }
        }

        SuratKeputusan::create([
            'sk_nomor' => $validated['sk_nomor'],
            'sk_tanggal' => $validated['sk_tanggal'],
            'sk_dokumen' => $suratKeputusan ?? null,
            'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
            'id_organization' => $perguruanTinggi->id,
        ]);

        session()->flash('success', 'Perguruan Tinggi berhasil ditambahkan.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('perguruan-tinggi.index')
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $organisasi = Organisasi::select(
            'id',
            'organisasi_kode',
            'organisasi_nama',
            'organisasi_nama_singkat',
            'organisasi_email',
            'organisasi_telp',
            'organisasi_kota',
            'organisasi_status',
            'organisasi_alamat',
            'parent_id',
            'organisasi_logo',
            'organisasi_website',
            'organisasi_berubah_id',
            'organisasi_bentuk_pt'
        )->with(
            'parent:id,organisasi_kode,organisasi_nama,organisasi_email,organisasi_telp,organisasi_status,organisasi_alamat,organisasi_kota'
        )->with([
            'prodis' => function ($query) {
                $query->select('id', 'id_organization', 'prodi_kode', 'prodi_nama', 'prodi_jenjang', 'prodi_periode', 'prodi_active_status')
                    ->orderBy('created_at', 'asc')
                    ->with('prodiStatus');
            },
            'bentukPt' => function ($query) {
                $query->select('id', 'bentuk_nama')->first();
            }
        ])->with(['akreditasis'])->findOrFail($id);

        if ($user->hasRole('Badan Penyelenggara')) {
            $bp = Organisasi::where('id', $user->id_organization)->first();
            if ($organisasi->parent_id != $bp->id) {
                $pt = Organisasi::where('parent_id', $user->id_organization)->get();
                $listPt = [];
                foreach ($pt as $item) {
                    $berubah = json_decode($item->organisasi_berubah_id, true);
                    $listPt = array_merge($listPt, $berubah);
                }
                if (!in_array($organisasi->id, $listPt)) {
                    return abort(403);
                }
            }
        }

        if ($user->hasRole('Perguruan Tinggi') && $organisasi->id != $user->id_organization) {
            $pt = Organisasi::where('id', $user->id_organization)->first();
            $listPt = json_decode($pt->organisasi_berubah_id, true);
            if (!in_array($organisasi->id, $listPt)) {
                return abort(403);
            }
        }

        $berubahIds = json_decode($organisasi->organisasi_berubah_id, true);

        if (!empty($berubahIds)) {
            $berubahOrganisasi = Organisasi::whereIn('id', $berubahIds)
                ->select(
                    'id',
                    'organisasi_kode',
                    'organisasi_nama',
                    'organisasi_kota',
                    'organisasi_berubah_status'
                )
                ->orderBy('organisasi_nama', 'asc')
                ->get();
        } else {
            $berubahOrganisasi = collect();
        }

        $akreditasi = DB::table('akreditasis')
            ->where('akreditasis.id_organization', $id)
            ->where('akreditasis.id_prodi', null)
            ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
            ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
            ->select('akreditasis.id', 'akreditasis.akreditasi_sk', 'akreditasis.akreditasi_tgl_akhir', 'akreditasis.akreditasi_status', 'lembaga_akreditasis.lembaga_nama_singkat', 'peringkat_akreditasis.peringkat_nama')
            ->orderBy('akreditasis.akreditasi_tgl_akhir')
            ->get();

        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id_organization', $id)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.id', 'surat_keputusans.sk_nomor', 'surat_keputusans.sk_tanggal', 'jenis_surat_keputusans.jsk_nama')
            ->get();

        $pimpinan = PimpinanOrganisasi::where('id_organization', $id)
            ->with([
                'jabatan' => function ($query) {
                    $query->select('id', 'jabatan_nama')->get();
                }
            ])->get();

        $prodis = DB::table('program_studis')
            ->where('program_studis.id_organization', $id)
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
            ->leftJoin('prodi_statuses', 'program_studis.prodi_active_status', '=', 'prodi_statuses.id')
            ->where(function ($query) {
                $query->whereNull('organisasis.tampil')
                    ->orWhereNot('organisasis.tampil', 0);
            })
            // ->orderBy('organisasis.organisasi_kode')
            ->orderBy('program_studis.prodi_nama', 'asc')
            ->select(
                'program_studis.id as id',
                'organisasis.organisasi_kode as kode_pt',
                'organisasis.organisasi_nama as nama_pt',
                'program_studis.prodi_kode as prodi_kode',
                'program_studis.prodi_nama as prodi_nama',
                'program_studis.prodi_jenjang as prodi_jenjang',
                'akreditasis.akreditasi_sk as no_sk_akreditasi',
                'program_studis.prodi_periode as prodi_periode',
                'prodi_statuses.prodi_status_nama as status',
                'akreditasis.akreditasi_tgl_awal as tgl_terbit_sk_akreditasi',
                'peringkat_akreditasis.peringkat_nama as akreditasi',
                'akreditasis.akreditasi_tgl_akhir as tgl_akhir_sk_akreditasi',
            )->get();

        $perkaras = Perkara::where('id_organization', $id)
            ->select('id', 'title', 'no_perkara', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Organisasi.PerguruanTinggi.Show', [
            'organisasi' => $organisasi,
            'berubahOrganisasi' => $berubahOrganisasi,
            'akreditasi' => $akreditasi,
            'sk' => $sk,
            'pimpinan' => $pimpinan,
            'prodis' => $prodis,
            'perkaras' => $perkaras
        ]);

        // return response()->json([
        //     'organisasi' => $organisasi,
        //     'berubahOrganisasi' => $berubahOrganisasi,
        //     'akreditasi' => $akreditasi,
        //     'sk' => $sk,
        //     'pimpinan' => $pimpinan,
        //     'prodis' => $prodis,
        //     'perkaras' => $perkaras
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPenyatuan(string $id)
    {
        $perguruanTinggi = Organisasi::findOrFail($id);

        $user = Auth::user();
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        $kotas =  Kota::select(
            'id',
            'nama'
        )->get();

        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            // ->where('id', $user->id_organization)
            ->select('id', 'organisasi_nama')
            ->get();

        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->where(function ($q) {
                $q->whereNull('tampil')
                    ->orWhereNot('tampil', 0);
            })
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        $bentukPt = BentukPt::all();

        return view('Organisasi.PerguruanTinggi.EditPenyatuan', [
            'perguruanTinggi' => $perguruanTinggi,
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'perguruanTinggis' => $perguruanTinggis,
            'kotas' => $kotas,
            'jenis' => $jenis,
            'bentukPt' => $bentukPt
        ]);
    }

    public function edit(string $id)
    {
        $perguruanTinggi = Organisasi::findOrFail($id);
        $kotas = Kota::select('id', 'nama')->orderBy('nama', 'asc')->get();
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)->get();
        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->where(function ($q) {
                $q->whereNull('tampil')
                    ->orWhereNot('tampil', 0);
            })->get();
        $skTypes = JenisSuratKeputusan::all();
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();
        $bentukPt = BentukPt::all();

        $user = Auth::user();
        $changeStatus = true;

        if ($user->hasRole('Badan Penyelenggara') || $user->hasRole('Perguruan Tinggi')) {
            $changeStatus = false;
        }

        return view('Organisasi.PerguruanTinggi.Edit', [
            'perguruanTinggi' => $perguruanTinggi,
            'kotas' => $kotas,
            'perguruanTinggis' => $perguruanTinggis,
            'skTypes' => $skTypes,
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'jenis' => $jenis,
            'bentukPt' => $bentukPt,
            'changeStatus' => $changeStatus
        ]);
    }

    // public function validationUpdatePenyatuan(Request $request, string $id)
    // {
    //     $validator = \Validator::make($request->all(), [
    //         'organisasi_berubah_id' => 'required|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()->toArray(),
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //     ]);
    // }

    public function validationUpdate(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'organisasi_kode' => 'required|string|max:6',
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|string|max:255',
            'organisasi_telp' => 'required|string|max:255',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|string|max:255',
            'organisasi_status' => 'nullable|string|max:20',
            'organisasi_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'organisasi_bentuk_pt' => 'required|exists:bentuk_pts,id',
            'parent_id' => 'nullable|exists:organisasis,id',
        ], [
            'organisasi_kode.required' => 'Kode Perguruan Tinggi harus diisi.',
            'organisasi_kode.max' => 'Kode Perguruan Tinggi harus terdiri dari 6 karakter.',
            'organisasi_nama.required' => 'Nama Perguruan Tinggi harus diisi.',
            'organisasi_alamat.required' => 'Alamat Perguruan Tinggi harus diisi.',
            'organisasi_email.required' => 'Email Perguruan Tinggi harus diisi.',
            'organisasi_email.email' => 'Format email tidak valid.',
            'organisasi_telp.required' => 'Nomor Telepon Perguruan Tinggi harus diisi.',
            'organisasi_kota.required' => 'Kota Perguruan Tinggi harus diisi.',
            'organisasi_logo.image' => 'Logo Perguruan Tinggi harus berupa gambar.',
            'organisasi_logo.mimes' => 'Logo Perguruan Tinggi harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Logo Perguruan Tinggi tidak boleh lebih dari 2MB.',
            'organisasi_status.max' => 'Status Perguruan Tinggi tidak valid.',
            'organisasi_website.url' => 'Format website tidak valid.',
            'organisasi_bentuk_pt.required' => 'Bentuk Perguruan Tinggi harus diisi.',
            'organisasi_bentuk_pt.exists' => 'Bentuk Perguruan Tinggi tidak valid.',
            'parent_id.exists' => 'Badan Penyelenggara tidak valid.',
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
    public function updatePenyatuan(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'organisasi_berubah_id' => 'required|array',
            'sk_nomor' => 'required|string|max:255',
            'sk_tanggal' => 'required|date',
            'sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'organisasi_berubah_id.required' => 'Perguruan Tinggi tujuan wajib dipilih.',
            'organisasi_berubah_id.array' => 'Format Perguruan Tinggi tujuan tidak valid.',
            'sk_nomor.required' => 'Nomor Surat Keputusan wajib diisi.',
            'sk_nomor.string' => 'Nomor Surat Keputusan harus berupa teks.',
            'sk_nomor.max' => 'Nomor Surat Keputusan tidak boleh lebih dari 255 karakter.',
            'sk_tanggal.required' => 'Tanggal Surat Keputusan wajib diisi.',
            'sk_tanggal.date' => 'Tanggal Surat Keputusan harus berupa format tanggal yang valid.',
            'sk_dokumen.file' => 'Dokumen SK harus berupa file.',
            'sk_dokumen.mimes' => 'Dokumen SK hanya boleh berupa file PDF, DOC, atau DOCX.',
            'sk_dokumen.max' => 'Ukuran Dokumen SK tidak boleh lebih dari 2MB.',
        ]);

        $organisasiBerubah = Organisasi::where('id', $request->organisasi_berubah_id)->first();
        $organisasi = Organisasi::where('id', $request->organisasi_pt)->first();

        $organisasi->update([
            'organisasi_status' => 'Alih Bentuk',
            'organisasi_berubah_status' => 'Penyatuan'
        ]);

        $existingData = json_decode($organisasiBerubah->organisasi_berubah_id, true) ?? [];
        $updatedData = array_unique(array_merge($existingData, [$organisasi->id]));

        $organisasiBerubah->update([
            'organisasi_berubah_id' => json_encode($updatedData),
        ]);

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        SuratKeputusan::create([
            'sk_nomor' => $request->sk_nomor,
            'sk_tanggal' => $request->sk_tanggal,
            'sk_dokumen' => $suratKeputusan ?? null,
            'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
            'id_organization' => $organisasi->id,
        ]);

        return redirect()->route('perguruan-tinggi.show', ['id' => $id])->with('success', 'Perubahan berhasil');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'organisasi_kode' => 'required|string|max:6',
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|string|max:255',
            'organisasi_telp' => 'required|string|max:255',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|string|max:255',
            'organisasi_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'organisasi_bentuk_pt' => 'required|exists:bentuk_pts,id',
            'parent_id' => 'nullable|exists:organisasis,id',
        ], [
            'organisasi_kode.required' => 'Kode Perguruan Tinggi harus diisi.',
            'organisasi_kode.max' => 'Kode Perguruan Tinggi harus terdiri dari 6 karakter.',
            'organisasi_nama.required' => 'Nama Perguruan Tinggi harus diisi.',
            'organisasi_alamat.required' => 'Alamat Perguruan Tinggi harus diisi.',
            'organisasi_email.required' => 'Email Perguruan Tinggi harus diisi.',
            'organisasi_email.email' => 'Format email tidak valid.',
            'organisasi_telp.required' => 'Nomor Telepon Perguruan Tinggi harus diisi.',
            'organisasi_kota.required' => 'Kota Perguruan Tinggi harus diisi.',
            'organisasi_logo.image' => 'Logo Perguruan Tinggi harus berupa gambar.',
            'organisasi_logo.mimes' => 'Logo Perguruan Tinggi harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Logo Perguruan Tinggi tidak boleh lebih dari 2MB.',
            'organisasi_website.url' => 'Format website tidak valid.',
            'organisasi_bentuk_pt.required' => 'Bentuk Perguruan Tinggi harus diisi.',
            'organisasi_bentuk_pt.exists' => 'Bentuk Perguruan Tinggi tidak valid.',
            'parent_id.exists' => 'Badan Penyelenggara tidak valid.',
        ]);

        $organisasis = Organisasi::findOrFail($id);
        if ($request->parent_id == $organisasis->parent_id || $organisasis->parent_id == null) {
            if ($request->hasFile('organisasi_logo')) {
                $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
                $validated['organisasi_logo'] = $logoPath;
                DB::table('organisasis')
                    ->where('id', $id)
                    ->update([
                        'organisasi_kode' => $validated['organisasi_kode'],
                        'organisasi_nama' => $validated['organisasi_nama'],
                        'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
                        'organisasi_email' => $validated['organisasi_email'],
                        'organisasi_telp' => $validated['organisasi_telp'],
                        'organisasi_kota' => $validated['organisasi_kota'],
                        'organisasi_alamat' => $validated['organisasi_alamat'],
                        'organisasi_website' => $validated['organisasi_website'],
                        'organisasi_logo' => $validated['organisasi_logo'],
                        'organisasi_bentuk_pt' => $validated['organisasi_bentuk_pt'],
                        'parent_id' => $validated['parent_id'],
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('organisasis')
                    ->where('id', $id)
                    ->update([
                        'organisasi_kode' => $validated['organisasi_kode'],
                        'organisasi_nama' => $validated['organisasi_nama'],
                        'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
                        'organisasi_email' => $validated['organisasi_email'],
                        'organisasi_telp' => $validated['organisasi_telp'],
                        'organisasi_kota' => $validated['organisasi_kota'],
                        'organisasi_alamat' => $validated['organisasi_alamat'],
                        'organisasi_website' => $validated['organisasi_website'],
                        'organisasi_bentuk_pt' => $validated['organisasi_bentuk_pt'],
                        'parent_id' => $validated['parent_id'],
                        'updated_at' => now(),
                    ]);
            }

            session()->flash('success', 'Perguruan Tinggi berhasil diperbarui.');
        } else {
            if ($request->hasFile('organisasi_logo')) {
                $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
                $validated['organisasi_logo'] = $logoPath;
                DB::table('organisasis')
                    ->where('id', $id)
                    ->update([
                        'organisasi_kode' => $validated['organisasi_kode'],
                        'organisasi_nama' => $validated['organisasi_nama'],
                        'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
                        'organisasi_email' => $validated['organisasi_email'],
                        'organisasi_telp' => $validated['organisasi_telp'],
                        'organisasi_kota' => $validated['organisasi_kota'],
                        'organisasi_alamat' => $validated['organisasi_alamat'],
                        'organisasi_website' => $validated['organisasi_website'],
                        'organisasi_logo' => $validated['organisasi_logo'],
                        'organisasi_bentuk_pt' => $validated['organisasi_bentuk_pt'],
                        'parent_id' => $validated['parent_id'],
                        'updated_at' => now(),
                    ]);
                HistoriPt::create([
                    'id_organization' => $organisasis->id,
                    'parent_id_lama' => $organisasis->parent_id,
                    'parent_id_baru' => $request->parent_id,
                    'status' => 'Alih Kelola'
                ]);
            } else {
                DB::table('organisasis')
                    ->where('id', $id)
                    ->update([
                        'organisasi_kode' => $validated['organisasi_kode'],
                        'organisasi_nama' => $validated['organisasi_nama'],
                        'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
                        'organisasi_email' => $validated['organisasi_email'],
                        'organisasi_telp' => $validated['organisasi_telp'],
                        'organisasi_kota' => $validated['organisasi_kota'],
                        'organisasi_alamat' => $validated['organisasi_alamat'],
                        'organisasi_website' => $validated['organisasi_website'],
                        'organisasi_bentuk_pt' => $validated['organisasi_bentuk_pt'],
                        'parent_id' => $validated['parent_id'],
                        'updated_at' => now(),
                    ]);

                HistoriPt::create([
                    'id_organization' => $organisasis->id,
                    'parent_id_lama' => $organisasis->parent_id,
                    'parent_id_baru' => $request->parent_id,
                    'status' => 'Alih Kelola'
                ]);
            }
            session()->flash('success', 'Perguruan Tinggi berhasil diperbarui.');
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('perguruan-tinggi.show', ['id' => $id])
        ]);
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
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);
        $file = $request->file('file');
        Excel::import(new PtImport, $file);
        return redirect()->route('perguruan-tinggi.index')->with('success', 'Perguruan Tinggi berhasil disimpan.');
    }

    public function exportExcel()
    {
        $fileName = 'Perguruan Tinggi_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new PtExport, $fileName);
    }
}
