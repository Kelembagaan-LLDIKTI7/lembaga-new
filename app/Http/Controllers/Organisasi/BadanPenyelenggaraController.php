<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Imports\BpImport;
use App\Models\Akta;
use App\Models\JenisSuratKeputusan;
use App\Models\Kota;
use App\Models\Organisasi;
use App\Models\Perkara;
use App\Models\PimpinanOrganisasi;
use App\Models\Skbp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function validationStore(Request $request)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'nullable|string|max:255',
            'organisasi_telp' => 'nullable|string|max:20',
            'organisasi_alamat' => 'required|string',
            'organisasi_kota' => 'required|string',
            'organisasi_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'akta_nomor' => 'required|string|max:255',
            'akta_tanggal' => 'required|date',
            'akta_nama_notaris' => 'required|string|max:255',
            'kotaAkta' => 'required|string',
            'akta_jenis' => 'required|string|in:Pendirian,Perubahan',
            'aktaDokumen' => 'nullable|string',
            'akta_keterangan' => 'nullable|string',
            'selectedBP' => 'nullable',
            'organisasi_website' => 'nullable',
        ], [
            'organisasi_nama.required' => 'Nama harus diisi.',
            'organisasi_nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'organisasi_email.required' => 'Email harus diisi.',
            'organisasi_email.email' => 'Email harus valid.',
            'organisasi_email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'organisasi_telp.required' => 'Telepon harus diisi.',
            'organisasi_telp.max' => 'Telepon tidak boleh lebih dari 20 karakter.',
            'organisasi_alamat.required' => 'Alamat harus diisi.',
            'organisasi_kota.required' => 'Kota harus diisi.',
            'organisasi_logo.required' => 'Logo organisasi harus diunggah.',
            'organisasi_logo.image' => 'File logo harus berupa gambar.',
            'organisasi_logo.mimes' => 'File logo harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Ukuran file logo tidak boleh lebih dari 2 MB.',
            'akta_nomor.required' => 'Nomor akta harus diisi.',
            'akta_nomor.max' => 'Nomor akta tidak boleh lebih dari 255 karakter.',
            'akta_tanggal.required' => 'Tanggal akta harus diisi.',
            'akta_nama_notaris.required' => 'Nama notaris harus diisi.',
            'akta_nama_notaris.max' => 'Nama notaris tidak boleh lebih dari 255 karakter.',
            'kotaAkta.required' => 'Kota akta harus diisi.',
            'akta_jenis.required' => 'Jenis akta harus diisi.',
            'akta_jenis.in' => 'Jenis akta harus dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('badan-penyelenggara.store'),
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
            'organisasi_email' => 'nullable|string|max:255',
            'organisasi_telp' => 'nullable|string|max:20',
            'organisasi_alamat' => 'required|string',
            'organisasi_kota' => 'required|string',
            'organisasi_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'akta_nomor' => 'required|string|max:255',
            'akta_tanggal' => 'required|date',
            'akta_nama_notaris' => 'required|string|max:255',
            'kotaAkta' => 'required|string',
            'akta_jenis' => 'required|string|in:Pendirian,Perubahan',
            'aktaDokumen' => 'nullable|string',
            'akta_keterangan' => 'nullable|string',
            'selectedBP' => 'nullable',
            'organisasi_website' => 'nullable',
        ], [
            'organisasi_nama.required' => 'Nama harus diisi.',
            'organisasi_nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'organisasi_email.required' => 'Email harus diisi.',
            'organisasi_email.email' => 'Email harus valid.',
            'organisasi_email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'organisasi_telp.required' => 'Telepon harus diisi.',
            'organisasi_telp.max' => 'Telepon tidak boleh lebih dari 20 karakter.',
            'organisasi_alamat.required' => 'Alamat harus diisi.',
            'organisasi_kota.required' => 'Kota harus diisi.',
            'organisasi_logo.required' => 'Logo organisasi harus diunggah.',
            'organisasi_logo.image' => 'File logo harus berupa gambar.',
            'organisasi_logo.mimes' => 'File logo harus berformat jpeg, png, jpg, atau gif.',
            'organisasi_logo.max' => 'Ukuran file logo tidak boleh lebih dari 2 MB.',
            'akta_nomor.required' => 'Nomor akta harus diisi.',
            'akta_nomor.max' => 'Nomor akta tidak boleh lebih dari 255 karakter.',
            'akta_tanggal.required' => 'Tanggal akta harus diisi.',
            'akta_nama_notaris.required' => 'Nama notaris harus diisi.',
            'akta_nama_notaris.max' => 'Nama notaris tidak boleh lebih dari 255 karakter.',
            'kotaAkta.required' => 'Kota akta harus diisi.',
            'akta_jenis.required' => 'Jenis akta harus diisi.',
            'akta_jenis.in' => 'Jenis akta harus dipilih.',
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
        } else {
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
                'organisasi_type_id' => 2,
                'users_id' => Auth::user()->id,
                'organisasi_berubah_status' => $validatedData['selectedBP'],
            ]);
        }

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
            'akta_dokumen' => $validatedData['aktaDokumen'] ?? null,
            'akta_keterangan' => $validatedData['akta_keterangan'],
            'id_organization' => $bp->id,
            'id_user' => Auth::user()->id,
        ]);

        session()->flash('success', 'Badan Penyelenggara berhasil ditambahkan.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('badan-penyelenggara.store'),
        ]);
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

        $perkaras = Perkara::where('id_organization', $id)
            ->select('id', 'title', 'no_perkara', 'tanggal_kejadian', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        // return response()->json([
        //     'badanPenyelenggaras' => $badanPenyelenggaras,
        //     'pimpinan' => $pimpinan,
        //     'akta' => $akta,
        //     'perkara' => $perkara,
        // ]);

        return view('Organisasi.BadanPenyelenggara.Show', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'pimpinan' => $pimpinan,
            'akta' => $akta,
            'skbp' => $skbp,
            'perkaras' => $perkaras,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bp = DB::table('organisasis')
            ->where('id', $id)
            ->first();

        $kotas = DB::table('kotas')
            ->select('id', 'nama')
            ->get();

        return view('Organisasi.BadanPenyelenggara.Edit', [
            'bp' => $bp,
            'kotas' => $kotas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'nullable|email|max:255',
            'organisasi_telp' => 'nullable|string|max:20',
            'organisasi_alamat' => 'nullable|string',
            'organisasi_kota' => 'nullable|string',
            'organisasi_website' => 'nullable',
        ]);

        DB::table('organisasis')
            ->where('id', $id)
            ->update([
                'organisasi_nama' => $validatedData['organisasi_nama'],
                'organisasi_nama_singkat' => $validatedData['organisasi_nama_singkat'] ?? null,
                'organisasi_email' => $validatedData['organisasi_email'] ?? null,
                'organisasi_telp' => $validatedData['organisasi_telp'] ?? null,
                'organisasi_website' => $validatedData['organisasi_website'] ?? null,
                'organisasi_alamat' => $validatedData['organisasi_alamat'] ?? null,
                'organisasi_kota' => $validatedData['organisasi_kota'] ?? null,
                'updated_at' => now(),
                'users_id' => Auth::user()->id,
            ]);

        return redirect()->route('badan-penyelenggara.show', ['id' => $id])->with('success', 'Badan Penyelenggara berhasil ditambahkan.');
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
