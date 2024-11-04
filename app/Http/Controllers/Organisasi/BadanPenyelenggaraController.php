<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Imports\BpImport;
use App\Models\Akta;
use App\Models\JenisSuratKeputusan;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\PimpinanOrganisasi;
use App\Models\Skbp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

class BadanPenyelenggaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
            )
            ->orderBy('organisasi_nama', 'asc');

        if ($user->hasRole('Badan Penyelenggara')) {
            $query->where('id', $user->id_organization);
        }

        $badanPenyelenggaras = $query->get();

        return view('Organisasi.BadanPenyelenggara.Index', ['badanPenyelenggaras' => $badanPenyelenggaras]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis = JenisSuratKeputusan::select(
            'id',
            'jsk_nama'
        )->get();

        $kotas =  Kota::select(
            'id',
            'nama'
        )->get();

        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->where('organisasi_status', 'Aktif')
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        return view('Organisasi.BadanPenyelenggara.Create', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'kotas' => $kotas,
            'jenis' => $jenis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|email|max:255',
            'organisasi_telp' => 'required|string|max:20',
            'organisasi_alamat' => 'required|string',
            'organisasi_kota' => 'required|string',
            'organisasi_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'akta_nomor' => 'required|string|max:255',
            'akta_tanggal' => 'required|date',
            'akta_nama_notaris' => 'required|string|max:255',
            'kotaAkta' => 'required|string',
            'akta_jenis' => 'required|string|in:Pendirian,Perubahan',
            'aktaDokumen' => 'required|mimes:pdf,doc,docx|max:2048',
            'selectedBP' => 'nullable',
            'organisasi_website' => 'nullable',
        ]);

        $bpLama = Organisasi::where('id', $validatedData['selectedBP'])
            ->where('organisasi_status', 'Aktif')
            ->select('id', 'organisasi_nama')
            ->with(['children' => function ($query) {
                $query->select(
                    'id',
                    'parent_id',
                    'organisasi_nama'
                );
            }])->first();

        if ($request->hasFile('organisasi_logo')) {
            $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
            $validatedData['organisasi_logo'] = $logoPath;
        }

        if ($request->hasFile('ataDokumen')) {
            $aktaPath = $request->file('aktaDokumen')->store('akta', 'public');
            $validatedData['akta_dokumen'] = $aktaPath;
        }

        $bp = Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_nama' => $validatedData['organisasi_nama'],
            'organisasi_nama_singkat' => $validatedData['organisasi_nama_singkat'] ?? null,
            'organisasi_email' => $validatedData['organisasi_email'],
            'organisasi_telp' => $validatedData['organisasi_telp'],
            'organisasi_website' => $validatedData['organisasi_website'],
            'organisasi_alamat' => $validatedData['organisasi_alamat'],
            'organisasi_kota' => $validatedData['organisasi_kota'],
            'organisasi_status' => 'Aktif',
            'organisasi_logo' => $validatedData['organisasi_logo'],
            'organisasi_type_id' => 2,
            'users_id' => Auth::user()->id,
            'organisasi_berubah_status' => $validatedData['selectedBP'],
        ]);

        if ($bpLama) {
            $bpLama->update([
                'organisasi_status' => 'Tidak',
            ]);

            foreach ($bpLama->children as $child) {
                $child->update([
                    'parent_id' => $bp->id,
                ]);
            }
        }

        Akta::create([
            'akta_nomor' => $validatedData['akta_nomor'],
            'akta_tanggal' => $validatedData['akta_tanggal'],
            'akta_nama_notaris' => $validatedData['akta_nama_notaris'],
            'akta_kota_notaris' => $validatedData['kotaAkta'],
            'akta_jenis' => $validatedData['akta_jenis'],
            'akta_status' => 'Aktif',
            'akta_dokumen' => $validatedData['aktaDokumen'],
            'id_organization' => $bp->id,
            'id_user' => Auth::user()->id,
        ]);

        return redirect()->route('badan-penyelenggara.index')->with('success', 'Badan Penyelenggara berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_status',
                'organisasi_alamat',
                'organisasi_kota',
            )
            ->with([
                'children' => function ($query) {
                    $query->select(
                        'id',
                        'organisasi_nama',
                        'organisasi_nama_singkat',
                        'organisasi_kode',
                        'organisasi_email',
                        'organisasi_telp',
                        'organisasi_kota',
                        'organisasi_alamat',
                        'organisasi_website',
                        'organisasi_logo',
                        'organisasi_status',
                        'organisasi_type_id',
                        'parent_id',
                    );
                },
                'referensi' => function ($query) {
                    $query->select(
                        'id',
                        'organisasi_nama',
                        'organisasi_nama_singkat',
                        'organisasi_kode',
                        'organisasi_email',
                        'organisasi_telp',
                        'organisasi_kota',
                        'organisasi_alamat',
                        'organisasi_website',
                        'organisasi_logo',
                        'organisasi_status',
                        'organisasi_type_id',
                        'parent_id',
                        'organisasi_berubah_status'
                    );
                }
            ])
            ->where('id', $id)
            ->first();

        $pimpinan = PimpinanOrganisasi::where('id_organization', $id)
            ->with([
                'jabatan' => function ($query) {
                    $query->select('id', 'jabatan_nama')->get();
                }
            ])->get();

        $akta = Akta::where('id_organization', $id)
            ->select(['id', 'akta_nomor', 'akta_tanggal', 'akta_status'])
            ->with(['skKumham'])
            ->get();

        $skbp = Skbp::where('id_organization', $id)
            ->get();

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     'pimpinan' => $pimpinan,
        //     'akta' => $akta
        // ]);

        return view('Organisasi.BadanPenyelenggara.Show', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'pimpinan' => $pimpinan,
            'akta' => $akta,
            'skbp' => $skbp,
        ]);
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');

        Excel::import(new BpImport, $file);

        return redirect()->route('badan-penyelenggara.index')->with('success', 'Badan Penyelenggara berhasil disimpan.');
    }
}
