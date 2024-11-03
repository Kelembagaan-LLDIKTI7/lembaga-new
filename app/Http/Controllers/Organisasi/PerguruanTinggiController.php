<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Imports\PtImport;
use App\Models\BentukPt;
use App\Models\JenisSuratKeputusan;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\PimpinanOrganisasi;
use App\Models\SuratKeputusan;
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
            ->select(
                'id',
                'organisasi_nama as pt_nama',
                'organisasi_nama_singkat',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
                'parent_id'
            )
            ->with('parent:id,organisasi_nama')
            ->orderBy('pt_nama', 'asc');
    
        // Filter data based on user role
        if ($user->hasRole('Perguruan Tinggi')) {
            $query->where('id', $user->id_organization); // Only show user’s own organization
        } elseif ($user->hasRole('Badan Penyelenggara')) {
            $query->where('parent_id', $user->id_organization); // Show organizations under their "Badan Penyelenggara"
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
            ->where('id', $user->id_organization)
            ->select('id', 'organisasi_nama')
            ->get();

        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        return view('Organisasi.PerguruanTinggi.Create', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'perguruanTinggis' => $perguruanTinggis,
            'kotas' => $kotas,
            'jenis' => $jenis
        ]);

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     'perguruanTinggis' => $perguruanTinggis,
        //     'kota' => $kota
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|email|max:255',
            'organisasi_telp' => 'required|string|max:15',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|url|max:255',
            'organisasi_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable',
            'sk_nomor' => 'required',
            'sk_tanggal' => 'required',
            'sk_dokumen' => 'required',
            'id_jenis_surat_keputusan' => 'required',
            'perubahan' => 'required'
        ]);

        if ($request->input('changeType') === 'penyatuan' || $request->input('changeType') === 'penggabungan') {
            $request->validate([
                'perguruan_tinggi_1' => 'required',
                'perguruan_tinggi_2' => 'required',
                'perguruan_tinggi_tambahan.*' => 'nullable'
            ]);
        }

        $organisasiBerubahId = [];

        if ($request->has('perguruan_tinggi_1') && $request->has('perguruan_tinggi_2')) {
            $organisasiBerubahId[] = $request->input('perguruan_tinggi_1');
            $organisasiBerubahId[] = $request->input('perguruan_tinggi_2');
        }

        if ($request->has('perguruan_tinggi_tambahan')) {
            foreach ($request->input('perguruan_tinggi_tambahan') as $ptTambahan) {
                if (!is_null($ptTambahan)) {
                    $organisasiBerubahId[] = $ptTambahan;
                }
            }
        }

        if (!empty($organisasiBerubahId)) {
            Organisasi::whereIn('id', $organisasiBerubahId)->update(['organisasi_status' => 'Tidak Aktif']);
        }

        if ($validated['perubahan'] === 'penyatuan') {
            $organisasiStatusBaru = 'Penyatuan';
        } elseif ($validated['perubahan'] === 'penggabungan') {
            $organisasiStatusBaru = 'Penggabungan';
        } else {
            $organisasiStatusBaru = 'Aktif';
        }

        if ($request->hasFile('organisasi_logo')) {
            $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
        }

        if ($request->hasFile('sk_dokumen')) {
            $suratKeputusan = $request->file('sk_dokumen')->store('surat_keputusan', 'public');
        }

        $perguruanTinggi = Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_nama' => $validated['organisasi_nama'],
            'organisasi_email' => $validated['organisasi_email'],
            'organisasi_telp' => $validated['organisasi_telp'],
            'organisasi_kota' => $validated['organisasi_kota'],
            'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
            'organisasi_website' => $validated['organisasi_website'] ?? null,
            'organisasi_alamat' => $validated['organisasi_alamat'],
            'organisasi_logo' => $logoPath ?? null,
            'organisasi_type_id' => 3,
            'organisasi_status' => $organisasiStatusBaru,
            'organisasi_berubah_id' => !empty($organisasiBerubahId) ? json_encode($organisasiBerubahId) : null,
            'parent_id' => $validated['parent_id'],
        ]);
        //     dd($perguruanTinggi);
        SuratKeputusan::create([
            'sk_nomor' => $validated['sk_nomor'],
            'sk_tanggal' => $validated['sk_tanggal'],
            'sk_dokumen' => $suratKeputusan,
            'id_jenis_surat_keputusan' => $validated['id_jenis_surat_keputusan'],
            'id_organization' => $perguruanTinggi->id,
        ]);
        return redirect()->route('perguruan-tinggi.index')->with('success', 'Perguruan Tinggi berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organisasi = Organisasi::select(
            'id',
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
            'parent:id,organisasi_nama,organisasi_email,organisasi_telp,organisasi_status,organisasi_alamat,organisasi_kota'
        )->with([
            'prodis' => function ($query) {
                $query->select('id', 'id_organization', 'prodi_nama', 'prodi_jenjang', 'prodi_active_status')
                    ->orderBy('created_at', 'asc');
            },
            'bentukPt' => function ($query) {
                $query->select('id', 'bentuk_nama')->first();
            }
        ])->with(['akreditasis'])->findOrFail($id);

        $berubahIds = json_decode($organisasi->organisasi_berubah_id, true);

        if (!empty($berubahIds)) {
            $berubahOrganisasi = Organisasi::whereIn('id', $berubahIds)
                ->select(
                    'id',
                    'organisasi_nama',
                    'organisasi_nama_singkat',
                    'organisasi_email',
                    'organisasi_telp',
                    'organisasi_kota',
                    'organisasi_status',
                    'organisasi_alamat',
                )
                ->orderBy('organisasi_nama', 'asc')
                ->get();
        } else {
            $berubahOrganisasi = collect();
        }

        $akreditasi = DB::table('akreditasis')
            ->where('akreditasis.id_organization', $id)
            ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
            ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
            ->select('akreditasis.id', 'akreditasis.akreditasi_sk', 'akreditasis.akreditasi_tgl_akhir', 'akreditasis.akreditasi_status', 'lembaga_akreditasis.lembaga_nama_singkat', 'peringkat_akreditasis.peringkat_nama')
            ->orderBy('akreditasis.created_at')
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

        return view('Organisasi.PerguruanTinggi.Show', [
            'organisasi' => $organisasi,
            'berubahOrganisasi' => $berubahOrganisasi,
            'akreditasi' => $akreditasi,
            'sk' => $sk,
            'pimpinan' => $pimpinan
        ]);

        // return response()->json([
        //     'organisasi' => $organisasi,
        //     'berubahOrganisasi' => $berubahOrganisasi,
        //     'akreditasi' => $akreditasi,
        //     'sk' => $sk,
        //     'pimpinan' => $pimpinan
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perguruanTinggi = Organisasi::findOrFail($id);
        $kotas = Kota::select('id', 'nama')->orderBy('nama', 'asc')->get();
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)->get();
        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)->get();
        $skTypes = JenisSuratKeputusan::all();
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();
        $bentukPt = BentukPt::all();

        return view('Organisasi.PerguruanTinggi.Edit', [
            'perguruanTinggi' => $perguruanTinggi,
            'kotas' => $kotas,
            'perguruanTinggis' => $perguruanTinggis,
            'skTypes' => $skTypes,
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'jenis' => $jenis,
            'bentukPt' => $bentukPt
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        // Find the existing Perguruan Tinggi record
        $perguruanTinggi = Organisasi::findOrFail($id);

        // Validate the incoming request data
        $validated = $request->validate([
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|email|max:255',
            'organisasi_telp' => 'required|string|max:15',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|url|max:255',
            'organisasi_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organisasi_bentuk_pt' => 'required|exists:bentuk_pts,id',
            'parent_id' => 'nullable',
        ]);

        // Handle file uploads for logo and document
        if ($request->hasFile('organisasi_logo')) {
            $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
            $validated['organisasi_logo'] = $logoPath; // Update the validated data with the new logo path
            // Update the Perguruan Tinggi record
            $perguruanTinggi->update($validated);
        } else {
            DB::table('organisasis')->where('id', $id)
                ->update([
                    'organisasi_nama' => $request->input('organisasi_nama'),
                    'organisasi_nama_singkat' => $request->input('organisasi_nama_singkat'),
                    'organisasi_email' => $request->input('organisasi_email'),
                    'organisasi_telp' => $request->input('organisasi_telp'),
                    'organisasi_kota' => $request->input('organisasi_kota'),
                    'organisasi_alamat' => $request->input('organisasi_alamat'),
                    'organisasi_website' => $request->input('organisasi_website'),
                    'organisasi_bentuk_pt' => $request->input('organisasi_bentuk_pt'),
                    'parent_id' => $request->input('parent_id'),
                ]);
        }

        return redirect()->route('perguruan-tinggi.show', ['id' => $id])->with('success', 'Perguruan Tinggi berhasil diperbarui.');
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
}
