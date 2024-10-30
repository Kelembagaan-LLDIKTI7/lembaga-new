<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AktaController extends Controller
{
    public function index()
    {
        $bp = DB::table('organization_types')
            ->where('org_type_name', 'Badan Penyelenggara')
            ->first();

        $org = DB::table('organisasis')
            ->where('org_type_id', $bp->id)
            ->get();

        $id_organization = session('org_akta');

        // dd($id_organization);

        if ($id_organization) {
            $akta = DB::table('aktas')
                ->where('id_organization', $id_organization)
                ->leftJoin('sk_kumhams', 'aktas.id', '=', 'sk_kumhams.id_akta')
                ->select('aktas.id as akta_id', 'aktas.akta_nomor', 'aktas.akta_tanggal', 'aktas.akta_jenis', 'sk_kumhams.id as kumham_id', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_tanggal')
                ->orderBy('aktas.created_at', 'desc')
                ->get();

            // dd($akta);

            return view('akta.index', compact('org', 'id_organization', 'akta'));
        }

        return view('akta.index', compact('org', 'id_organization'));
    }

    public function createSession(Request $request)
    {
        session(['org_uuid' => $request->id_organization]);
        session(['org_akta' => $request->id_organization]);
        session(['detail_org' => $request->detail_org]);

        return redirect()->route('akta.formCreate');
    }

    public function formCreate()
    {
        $id_organization = session('org_uuid');
        $akta = DB::table('aktas')
            ->where('id_organization', $id_organization)
            ->get();
        $kota = DB::table('kotas')->orderBy('nama', 'asc')->get();
        return view('akta.create', compact('kota', 'id_organization', 'akta'));
    }

    public function store(Request $request)
    {

        $id_user = Auth::user()->id;
        // dd($id_user);
        // dd($request->all());
        $request->validate([
            'akta_nomor' => 'required|string|unique:aktas,akta_nomor|max:50',
            'akta_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akta_nama_notaris' => 'required|string|max:100',
            'akta_kota_notaris' => 'required|string|max:100',
            'akta_jenis' => 'required',
            'akta_referensi' => 'nullable|uuid',
            'akta_dokumen' => 'required|mimes:pdf|max:2048'
        ], [
            'akta_nomor.required' => 'Nomor Akta harus diisi.',
            'akta_nomor.unique' => 'Nomor Akta sudah terdaftar.',
            'akta_nomor.max' => 'Nomor Akta maksimal 50 karakter.',
            'akta_tanggal.required' => 'Tanggal Akta harus diisi.',
            'akta_nama_notaris.required' => 'Nama Notaris harus diisi.',
            'akta_nama_notaris.max' => 'Nama Notaris maksimal 100 karakter.',
            'akta_kota_notaris.required' => 'Kota Notaris harus diisi.',
            'akta_kota_notaris.max' => 'Kota Notaris maksimal 100 karakter.',
            'akta_jenis.required' => 'Jenis Akta harus dipilih.',
            'akta_referensi.uuid' => 'Referensi Akta tidak valid.',
            'akta_dokumen.required' => 'File Akta harus diupload.',
            'akta_dokumen.mimes' => 'File Akta harus berformat PDF.',
            'akta_dokumen.max' => 'Ukuran File Akta maksimal 2MB.'
        ]);

        // Get ID User Login

        $id_akta = Str::uuid()->toString();
        $filename_akta = $this->generateFileName($request->file('akta_dokumen')->getClientOriginalExtension(), 'akta_dokumen', $id_akta);
        $id_organization = session('org_uuid');

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->akta_tanggal)->format('Y-m-d');

        // store session for organization
        session(['org_akta' => $id_organization]);

        DB::beginTransaction();
        try {
            DB::table('aktas')->insert([
                'id' => $id_akta,
                'akta_nomor' => $request->akta_nomor,
                'akta_tanggal' => $formatted_date,
                'akta_nama_notaris' => $request->akta_nama_notaris,
                'akta_kota_notaris' => $request->akta_kota_notaris,
                'id_organization' => $id_organization,
                'akta_jenis' => $request->akta_jenis,
                'akta_referensi' => $request->akta_referensi,
                'akta_dokumen' => $filename_akta,
                'id_user' => $id_user,
                'created_at' => now(),
            ]);

            $request->file('akta_dokumen')->storeAs('dokumen/akta/', $filename_akta, 'public');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data akta.');
        }

        return redirect()->route('badan-penyelenggara.show', ['id' => $id_organization]);
    }

    public function edit(Request $request)
    {
        session(['akta_uuid' => $request->id]);
        $akta = DB::table('aktas')->where('id', $request->id)->first();
        session(['org_akta' => $akta->id_organization]);
        return redirect()->route('akta.formEdit');
    }

    public function editForm()
    {
        $id_akta = session('akta_uuid');
        $id_organization = session('org_akta');
        $akta = DB::table('aktas')
            ->where('id', $id_akta)
            ->first();

        // dd($akta);

        $referensi = DB::table('aktas')
            ->where('id_organization', $id_organization)
            ->get();

        $kota = DB::table('kotas')->orderBy('nama', 'asc')->get();

        return view('akta.edit', compact('akta', 'kota', 'referensi', 'id_organization'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'akta_nomor' => 'required|string|max:50',
            'akta_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'akta_nama_notaris' => 'required|string|max:100',
            'akta_kota_notaris' => 'required|string|max:100',
            'akta_jenis' => 'required',
            'akta_referensi' => 'nullable|uuid',
            'akta_dokumen' => 'nullable|mimes:pdf|max:2048'
        ], [
            'akta_nomor.required' => 'Nomor Akta harus diisi.',
            'akta_nomor.max' => 'Nomor Akta maksimal 50 karakter.',
            'akta_tanggal.required' => 'Tanggal Akta harus diisi.',
            'akta_nama_notaris.required' => 'Nama Notaris harus diisi.',
            'akta_nama_notaris.max' => 'Nama Notaris maksimal 100 karakter.',
            'akta_kota_notaris.required' => 'Kota Notaris harus diisi.',
            'akta_kota_notaris.max' => 'Kota Notaris maksimal 100 karakter.',
            'akta_jenis.required' => 'Jenis Akta harus dipilih.',
            'akta_referensi.uuid' => 'Referensi Akta tidak valid.',
            'akta_dokumen.mimes' => 'File Akta harus berformat PDF.',
            'akta_dokumen.max' => 'Ukuran File Akta maksimal 2MB.'
        ]);

        $id_akta = session('akta_uuid');
        $formatted_date = Carbon::createFromFormat('d M, Y', $request->akta_tanggal)->format('Y-m-d');

        if ($id_akta == $request->akta_referensi) {
            return redirect()->back()->withErrors('error', 'Referensi Akta tidak boleh sama dengan Akta yang diedit.')->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('akta_dokumen')) {
                $filename_akta = $this->generateFileName($request->file('akta_dokumen')->getClientOriginalExtension(), 'akta_dokumen', $id_akta);

                DB::table('aktas')
                    ->where('id', $id_akta)
                    ->update([
                        'akta_nomor' => $request->akta_nomor,
                        'akta_tanggal' => $formatted_date,
                        'akta_nama_notaris' => $request->akta_nama_notaris,
                        'akta_kota_notaris' => $request->akta_kota_notaris,
                        'akta_jenis' => $request->akta_jenis,
                        'akta_dokumen' => $filename_akta,
                        'akta_referensi' => $request->akta_referensi,
                        'id_user' => Auth::user()->id,
                        'updated_at' => now()
                    ]);

                $request->file('akta_dokumen')->storeAs('dokumen/akta/', $filename_akta, 'public');
            } else {
                DB::table('aktas')
                    ->where('id', $id_akta)
                    ->update([
                        'akta_nomor' => $request->akta_nomor,
                        'akta_tanggal' => $formatted_date,
                        'akta_nama_notaris' => $request->akta_nama_notaris,
                        'akta_kota_notaris' => $request->akta_kota_notaris,
                        'akta_jenis' => $request->akta_jenis,
                        'akta_referensi' => $request->akta_referensi,
                        'id_user' => Auth::user()->id,
                        'updated_at' => now()
                    ]);
            }

            Log::info('Akta updated ' . $id_akta);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('akta.index')->with('error', 'Gagal mengubah data akta.');
        }

        $akta = DB::table('aktas')
            ->where('id', $id_akta)
            ->first();

        return redirect()->route('badan-penyelenggara.show', ['id' => $akta->id_organization]);
    }

    public function getAkta(Request $request)
    {
        $id = $request->input('id_organization');

        session(['org_uuid' => $id]);
        session(['org_akta' => $id]);

        $akta = DB::table('aktas')
            ->where('id_organization', $id)
            ->leftJoin('sk_kumhams', 'aktas.id', '=', 'sk_kumhams.id_akta')
            ->select('aktas.id as akta_id', 'aktas.akta_nomor', 'aktas.akta_tanggal', 'aktas.akta_jenis', 'sk_kumhams.id as kumham_id', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_tanggal', 'aktas.id_organization')
            ->get();

        return response()->json($akta);
    }

    public function getAktaDetail($id)
    {
        $akta = DB::table('aktas')
            ->where('aktas.id', $id) // tambahkan nama tabel 'aktas' sebelum 'id'
            ->leftJoin('sk_kumhams', 'sk_kumhams.id_akta', '=', 'aktas.id')
            ->select('aktas.*', 'sk_kumhams.id as kumham_id', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_perihal', 'sk_kumhams.kumham_tanggal', 'sk_kumhams.kumham_dokumen')
            ->first();

        return response()->json($akta);
    }

    public function delete(Request $request)
    {
        $akta = DB::table('aktas')
            ->where('id', $request->id)
            ->first();

        if ($akta) {
            DB::beginTransaction();
            try {
                DB::table('aktas')
                    ->where('id', $request->id)
                    ->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->route('akta.index')->with('error', 'Gagal menghapus data akta.');
            }
        }

        return redirect()->route('akta.index');
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_akta' => $request->akta_dokumen]);

        return redirect()->route('akta.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_akta');
        $path = storage_path('app/public/dokumen/akta/' . $filename);

        return response()->file($path);
    }
}
