<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Akreditasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AkreditasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pt = DB::table('organization_types')
            ->where('org_type_name', 'Perguruan Tinggi')
            ->first();

        $org = DB::table('organisasis')
            ->where('org_type_id', $pt->id)
            ->get();

        $id_organization = session('org_akreditasi');

        $id_prodi = session('prodi_akreditasi');

        if ($id_organization) {
            $akreditasi = DB::table('akreditasis')
                ->where('akreditasis.id_organization', $id_organization)
                ->where('akreditasis.id_prodi', $id_prodi)
                ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
                ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
                ->select('akreditasis.id as akreditasi_id', 'akreditasis.akreditasi_sk', 'akreditasis.akreditasi_tgl_awal', 'akreditasis.akreditasi_tgl_akhir', 'akreditasis.akreditasi_status', 'peringkat_akreditasis.id as peringkat_akreditasi_id', 'peringkat_akreditasis.peringkat_nama', 'lembaga_akreditasis.id as lembaga_akreditasi_id', 'lembaga_akreditasis.lembaga_nama')
                ->orderBy('akreditasis.created_at', 'desc')
                ->get();

            $prodi = DB::table('program_studis')
                ->where('id_organization', $id_organization)
                ->get();

            return view('akreditasi.index', compact('org', 'id_organization', 'akreditasi', 'prodi', 'id_prodi'));
        }

        return view('akreditasi.index', compact('org', 'id_organization'));
    }

    public function createSession(Request $request)
    {
        session(['org_akreditasi' => $request->id_organization]);
        session(['prodi_akreditasi' => $request->id_prodi]);
        session(['detail_org' => $request->detail_org]);

        return redirect()->route('akreditasi.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id_organization = session('org_akreditasi');
        $id_prodi = session('prodi_akreditasi');

        $lembaga = DB::table('lembaga_akreditasis')->get();
        $peringkat = DB::table('peringkat_akreditasis')->get();

        return view('akreditasi.create', compact('lembaga', 'peringkat', 'id_organization', 'id_prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_user = Auth::user()->id;

        $request->validate([
            'akreditasi_sk' => 'required|string|unique:akreditasis,akreditasi_sk|max:50',
            'akreditasi_tgl_awal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akreditasi_tgl_akhir' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akreditasi_dokumen' => 'required|mimes:pdf|max:10240',
            'akreditasi_status' => 'required',
            'id_organization' => 'required|uuid',
            'id_lembaga_akreditasi' => 'required|uuid|exists:lembaga_akreditasis,id',
            'id_peringkat_akreditasi' => 'required|uuid|exists:peringkat_akreditasis,id',
            'id_prodi' => 'nullable|uuid|exists:program_studis,id',
        ], [
            'akreditasi_sk.required' => 'Nomor SK Akreditasi wajib diisi',
            'akreditasi_sk.string' => 'Nomor SK Akreditasi harus berupa string',
            'akreditasi_sk.unique' => 'Nomor SK Akreditasi sudah terdaftar',
            'akreditasi_sk.max' => 'Nomor SK Akreditasi maksimal 50 karakter',
            'akreditasi_tgl_awal.required' => 'Tanggal Awal Akreditasi wajib diisi',
            'akreditasi_tgl_akhir.required' => 'Tanggal Akhir Akreditasi wajib diisi',
            'akreditasi_dokumen.required' => 'Dokumen Akreditasi wajib diisi',
            'akreditasi_dokumen.mimes' => 'Dokumen Akreditasi harus berupa file PDF',
            'akreditasi_dokumen.max' => 'Dokumen Akreditasi maksimal 10MB',
            'akreditasi_status.required' => 'Status Akreditasi wajib diisi',
            'akreditasi_status.in' => 'Status Akreditasi tidak valid',
            'id_organization.required' => 'Perguruan Tinggi wajib diisi',
            'id_organization.uuid' => 'Perguruan Tinggi harus berupa UUID',
            'id_lembaga_akreditasi.required' => 'Lembaga Akreditasi wajib diisi',
            'id_lembaga_akreditasi.uuid' => 'Lembaga Akreditasi harus berupa UUID',
            'id_lembaga_akreditasi.exists' => 'Lembaga Akreditasi tidak ditemukan',
            'id_peringkat_akreditasi.required' => 'Peringkat Akreditasi wajib diisi',
            'id_peringkat_akreditasi.uuid' => 'Peringkat Akreditasi harus berupa UUID',
            'id_peringkat_akreditasi.exists' => 'Peringkat Akreditasi tidak ditemukan',
            'id_prodi.uuid' => 'Program Studi harus berupa UUID',
            'id_prodi.exists' => 'Program Studi tidak ditemukan',
        ]);

        $formatted_tgl_awal = Carbon::createFromFormat('d M, Y', $request->akreditasi_tgl_awal)->format('Y-m-d');
        $formatted_tgl_akhir = Carbon::createFromFormat('d M, Y', $request->akreditasi_tgl_akhir)->format('Y-m-d');

        $id_akreditasi = Str::uuid()->toString();
        $filename_akreditasi = $this->generateFileName($request->file('akreditasi_dokumen')->getClientOriginalExtension(), 'akreditasi_dokumen', $id_akreditasi);

        $id_organization = session('org_akreditasi');

        DB::beginTransaction();
        try {
            DB::table('akreditasis')->insert([
                'id' => $id_akreditasi,
                'akreditasi_sk' => $request->akreditasi_sk,
                'akreditasi_tgl_awal' => $formatted_tgl_awal,
                'akreditasi_tgl_akhir' => $formatted_tgl_akhir,
                'akreditasi_status' => 'Berlaku',
                'akreditasi_dokumen' => $filename_akreditasi,
                'id_organization' => $id_organization,
                'id_lembaga_akreditasi' => $request->id_lembaga_akreditasi,
                'id_peringkat_akreditasi' => $request->id_peringkat_akreditasi,
                'id_prodi' => $request->id_prodi,
                'id_user' => $request->id_user,
            ]);

            $request->file('akreditasi_dokumen')->storeAs('dokumen/akreditasi/', $filename_akreditasi, 'public');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Akreditasi failed to create ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        if (session('detail_org') == '1') {
            session()->forget('detail_org');

            return redirect()->route('perguruan-tinggi.show', ['id' => $id_organization])->with('success', 'Akreditasi berhasil ditambahkan');
        } else if (session('detail_org') == '2') {
            session()->forget('detail_org');

            return redirect()->route('program-studi.show', ['id' => $request->id_prodi])->with('success', 'Akreditasi berhasil ditambahkan');
        } else {
            return redirect()->route('akreditasi.index')->with('success', 'Akreditasi berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $akreditasi = DB::table('akreditasis')
            ->where('akreditasis.id', $id)
            ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
            ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
            ->leftJoin('organisasis', 'organisasis.id', '=', 'akreditasis.id_organization')
            ->leftJoin('program_studis', 'program_studis.id', '=', 'akreditasis.id_prodi')
            ->select('akreditasis.*', 'peringkat_akreditasis.peringkat_nama', 'lembaga_akreditasis.lembaga_nama', 'organisasis.org_nama', 'program_studis.prodi_nama')
            ->first();

        return view('akreditasi.show', compact('akreditasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akreditasi = DB::table('akreditasis')
            ->where('id', $id)
            ->first();

        $lembaga = DB::table('lembaga_akreditasis')->get();
        $peringkat = DB::table('peringkat_akreditasis')->get();

        return view('akreditasi.edit', compact('akreditasi', 'lembaga', 'peringkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'akreditasi_sk' => 'required|string|max:50',
            'akreditasi_tgl_awal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akreditasi_tgl_akhir' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akreditasi_dokumen' => 'nullable|mimes:pdf|max:10240',
            'akreditasi_status' => 'required',
            'id_organization' => 'required|uuid',
            'id_lembaga_akreditasi' => 'required|uuid|exists:lembaga_akreditasis,id',
            'id_peringkat_akreditasi' => 'required|uuid|exists:peringkat_akreditasis,id',
            'id_prodi' => 'nullable|uuid|exists:program_studis,id',
        ], [
            'akreditasi_sk.required' => 'Nomor SK Akreditasi wajib diisi',
            'akreditasi_sk.string' => 'Nomor SK Akreditasi harus berupa string',
            'akreditasi_sk.unique' => 'Nomor SK Akreditasi sudah terdaftar',
            'akreditasi_sk.max' => 'Nomor SK Akreditasi maksimal 50 karakter',
            'akreditasi_tgl_awal.required' => 'Tanggal Awal Akreditasi wajib diisi',
            'akreditasi_tgl_akhir.required' => 'Tanggal Akhir Akreditasi wajib diisi',
            'akreditasi_dokumen.required' => 'Dokumen Akreditasi wajib diisi',
            'akreditasi_dokumen.mimes' => 'Dokumen Akreditasi harus berupa file PDF',
            'akreditasi_dokumen.max' => 'Dokumen Akreditasi maksimal 10MB',
            'akreditasi_status.required' => 'Status Akreditasi wajib diisi',
            'id_organization.required' => 'Perguruan Tinggi wajib diisi',
            'id_organization.uuid' => 'Perguruan Tinggi harus berupa UUID',
            'id_lembaga_akreditasi.required' => 'Lembaga Akreditasi wajib diisi',
            'id_lembaga_akreditasi.uuid' => 'Lembaga Akreditasi harus berupa UUID',
            'id_lembaga_akreditasi.exists' => 'Lembaga Akreditasi tidak ditemukan',
            'id_peringkat_akreditasi.required' => 'Peringkat Akreditasi wajib diisi',
            'id_peringkat_akreditasi.uuid' => 'Peringkat Akreditasi harus berupa UUID',
            'id_peringkat_akreditasi.exists' => 'Peringkat Akreditasi tidak ditemukan',
            'id_prodi.uuid' => 'Program Studi harus berupa UUID',
            'id_prodi.exists' => 'Program Studi tidak ditemukan',
        ]);

        $id_user = Auth::user()->id;
        $id_organization = session('org_akreditasi');
        $id_prodi = session('prodi_akreditasi');

        $formatted_tgl_awal = Carbon::createFromFormat('d M, Y', $request->akreditasi_tgl_awal)->format('Y-m-d');
        $formatted_tgl_akhir = Carbon::createFromFormat('d M, Y', $request->akreditasi_tgl_akhir)->format('Y-m-d');

        DB::beginTransaction();
        try {
            if ($request->hasFile('akreditasi_dokumen')) {
                $filename_akreditasi = $this->generateFileName($request->file('akreditasi_dokumen')->getClientOriginalExtension(), 'akreditasi_dokumen', $request->id);

                DB::table('akreditasis')
                    ->where('id', $request->id)
                    ->update([
                        'akreditasi_sk' => $request->akreditasi_sk,
                        'akreditasi_tgl_awal' => $formatted_tgl_awal,
                        'akreditasi_tgl_akhir' => $formatted_tgl_akhir,
                        'akreditasi_status' => $request->akreditasi_status,
                        'akreditasi_dokumen' => $filename_akreditasi,
                        'id_organization' => $id_organization,
                        'id_lembaga_akreditasi' => $request->id_lembaga_akreditasi,
                        'id_peringkat_akreditasi' => $request->id_peringkat_akreditasi,
                        'id_prodi' => $id_prodi,
                        'id_user' => $id_user,
                    ]);

                $request->file('akreditasi_dokumen')->storeAs('dokumen/akreditasi/', $filename_akreditasi, 'public');
            } else {
                DB::table('akreditasis')
                    ->where('id', $request->id)
                    ->update([
                        'akreditasi_sk' => $request->akreditasi_sk,
                        'akreditasi_tgl_awal' => $formatted_tgl_awal,
                        'akreditasi_tgl_akhir' => $formatted_tgl_akhir,
                        'akreditasi_status' => $request->akreditasi_status,
                        'id_organization' => $id_organization,
                        'id_lembaga_akreditasi' => $request->id_lembaga_akreditasi,
                        'id_peringkat_akreditasi' => $request->id_peringkat_akreditasi,
                        'id_prodi' => $id_prodi,
                        'id_user' => $id_user,
                    ]);
            }

            Log::info('Akreditasi updated ' . $request->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Akreditasi failed to update ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        $akre = DB::table('akreditasis')
            ->where('id', $request->id)
            ->first();

        if ($akre->id_prodi != null) {
            return redirect()->route('program-studi.show', ['id' => $akre->id_prodi])->with('success', 'Akreditasi berhasil diubah');
        } else {
            return redirect()->route('perguruan-tinggi.show', ['id' => $akre->id_organization])->with('success', 'Akreditasi berhasil diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $akreditasi = DB::table('akreditasis')
            ->where('id', $request->id)
            ->first();

        $id_organization = $akreditasi->id_organization;
        $id_prodi = $akreditasi->id_prodi;

        DB::beginTransaction();
        try {
            if ($akreditasi->akreditasi_dokumen) {
                Storage::disk('public')->delete('dokumen/akreditasi/' . $akreditasi->akreditasi_dokumen);
            }

            DB::table('akreditasis')
                ->where('id', $request->id)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Akreditasi failed to delete ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($id_prodi != null) {
            return redirect()->route('program-studi.show', ['id' => $id_prodi])->with('success', 'Akreditasi berhasil dihapus');
        } else {
            return redirect()->route('perguruan-tinggi.show', ['id' => $id_organization])->with('success', 'Akreditasi berhasil dihapus');
        }
    }

    public function getAkreditasi(Request $request)
    {
        $akreditasi = DB::table('akreditasis')
            ->where('akreditasis.id_organization', $request->id_organization)
            ->where('akreditasis.id_prodi', $request->id_prodi)
            ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
            ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
            ->select('akreditasis.id as akreditasi_id', 'akreditasis.akreditasi_sk', 'akreditasis.akreditasi_tgl_awal', 'akreditasis.akreditasi_tgl_akhir', 'akreditasis.akreditasi_status', 'peringkat_akreditasis.id as peringkat_akreditasi_id', 'peringkat_akreditasis.peringkat_nama', 'lembaga_akreditasis.id as lembaga_akreditasi_id', 'lembaga_akreditasis.lembaga_nama')
            ->orderBy('akreditasis.created_at', 'desc')
            ->get();

        return response()->json($akreditasi);
    }

    public function getAkreditasiDetail($id)
    {
        $akreditasi = DB::table('akreditasis')
            ->where('akreditasis.id', $id)
            ->leftJoin('peringkat_akreditasis', 'peringkat_akreditasis.id', '=', 'akreditasis.id_peringkat_akreditasi')
            ->leftJoin('lembaga_akreditasis', 'lembaga_akreditasis.id', '=', 'akreditasis.id_lembaga_akreditasi')
            ->leftJoin('organisasis', 'organisasis.id', '=', 'akreditasis.id_organization')
            ->leftJoin('program_studis', 'program_studis.id', '=', 'akreditasis.id_prodi')
            ->select('akreditasis.*', 'peringkat_akreditasis.peringkat_nama', 'lembaga_akreditasis.lembaga_nama', 'organisasis.org_nama', 'program_studis.prodi_nama')
            ->first();

        return response()->json($akreditasi);
    }

    public function getProdi(Request $request)
    {
        $prodi = DB::table('program_studis')
            ->where('id_organization', $request->id_organization)
            ->get();

        return response()->json($prodi);
    }

    public function pdfSession(Request $request)
    {
        session(['pdf_akreditasi' => $request->akreditasi_dokumen]);

        return redirect()->route('akreditasi.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_akreditasi');

        return response()->file(storage_path('app/public/dokumen/akreditasi/' . $filename));
    }
}
