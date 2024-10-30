<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class SuratKeputusanController extends Controller
{
    public function index()
    {
        $id_organization = session('org_sk');

        $checkUser = Auth::user();
        $checkOrg = Organisasi::findOrFail($checkUser->id_organization);

        if ($checkOrg->org_type_id == '1') {
            $permission = 1;
            $org = DB::table('organisasis as child')
                ->leftJoin('organisasis as parent', 'child.parent_id', '=', 'parent.id')
                ->where('child.org_type_id', '3')
                ->select('child.*', 'parent.id as parent_id', 'parent.org_nama_singkat as parent_name')
                ->get();
        } else if ($checkOrg->org_type_id == '2') {
            $permission = 2;
            $org = DB::table('organisasis as child')
                ->leftJoin('organisasis as parent', 'child.parent_id', '=', 'parent.id')
                ->where('child.org_type_id', '3')
                ->where('parent.id', $checkUser->id_organization)
                ->select('child.*', 'parent.id as parent_id', 'parent.org_nama_singkat as parent_name')
                ->get();
        } else if ($checkOrg->org_type_id == '3') {
            $permission = 3;
        }

        if ($id_organization) {
            $sk = DB::table('surat_keputusans')
                ->where('surat_keputusans.id_organization', $id_organization)
                ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
                ->select('surat_keputusans.*', 'jenis_surat_keputusans.jsk_nama')
                ->get();

            return view('surat-keputusan.index', compact('org', 'checkOrg', 'permission', 'id_organization', 'sk'));
        } else {
            $sk = [];

            return view('surat-keputusan.index', compact('org', 'checkOrg', 'permission', 'id_organization', 'sk'));
        }

        return view('surat-keputusan.index', compact('org', 'checkOrg', 'permission', 'id_organization', 'sk'));
    }

    public function createSession(Request $request)
    {
        session(['org_sk' => $request->id_organization]);
        session(['id_prodi' => $request->id_prodi]);
        session(['detail_org' => $request->detail_org]);

        return redirect()->route('surat-keputusan.createForm');
    }

    public function createForm()
    {
        $id_organization = session('org_sk');
        $id_prodi = session('id_prodi');
        $jenis = DB::table('jenis_surat_keputusans')->get();

        return view('surat-keputusan.create', compact('id_organization', 'id_prodi', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sk_nomor' => 'required|string|max:105|unique:surat_keputusans',
            'sk_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'sk_dokumen' => 'nullable|mimes:pdf|max:10240',
            'id_jenis_surat_keputusan' => 'required|uuid|exists:jenis_surat_keputusans,id',
        ], [
            'sk_nomor.required' => 'Nomor Surat Keputusan harus diisi.',
            'sk_nomor.unique' => 'Nomor Surat Keputusan sudah terdaftar.',
            'sk_nomor.max' => 'Nomor Surat Keputusan maksimal 105 karakter.',
            'sk_tanggal.required' => 'Tanggal Surat Keputusan harus diisi.',
            'sk_tanggal.date' => 'Format tanggal tidak valid.',
            'sk_dokumen.mimes' => 'File Surat Keputusan harus berformat PDF.',
            'sk_dokumen.max' => 'Ukuran File Surat Keputusan maksimal 10MB.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus dipilih.',
            'id_jenis_surat_keputusan.uuid' => 'Jenis Surat Keputusan harus berupa UUID.',
            'id_jenis_surat_keputusan.exists' => 'Jenis Surat Keputusan tidak ditemukan.',
        ]);

        $id_surat_keputusan = Str::uuid()->toString();

        if ($request->hasFile('sk_dokumen')) {
            $filename_surat_keputusan = $this->generateFileName($request->file('sk_dokumen')->getClientOriginalExtension(), 'sk_dokumen', $id_surat_keputusan);
        }
        $id_organization = session('org_sk');
        $id_prodi = session('id_prodi');

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->sk_tanggal)->format('Y-m-d');

        DB::beginTransaction();
        try {
            if ($request->hasFile('sk_dokumen')) {
                $request->file('sk_dokumen')->storeAs('dokumen/surat-keputusan/', $filename_surat_keputusan, 'public');
                $id_sk = DB::table('surat_keputusans')->insertGetId([
                    'id' => $id_surat_keputusan,
                    'sk_nomor' => $request->sk_nomor,
                    'sk_tanggal' => $formatted_date,
                    'sk_dokumen' => $filename_surat_keputusan,
                    'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                    'id_organization' => $id_organization,
                    'id_prodi' => $id_prodi,
                    'id_user' => Auth::user()->id,
                    'created_at' => now(),
                ]);
            } else {
                $id_sk = DB::table('surat_keputusans')->insertGetId([
                    'id' => $id_surat_keputusan,
                    'sk_nomor' => $request->sk_nomor,
                    'sk_tanggal' => $formatted_date,
                    'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                    'id_organization' => $id_organization,
                    'id_prodi' => $id_prodi,
                    'id_user' => Auth::user()->id,
                    'created_at' => now(),
                ]);
            }


            Log::info('Surat Keputusan created ' . $id_surat_keputusan);

            session(['org_sk' => $id_organization]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        $sk = DB::table('surat_keputusans')
            ->where('id', $id_sk)
            ->first();

        if ($sk->id_prodi != null) {
            return redirect()->route('program-studi.show', ['id' => $sk->id_prodi])->with('success', 'Akreditasi berhasil diubah');
        } else {
            return redirect()->route('perguruan-tinggi.show', ['id' => $sk->id_organization])->with('success', 'Akreditasi berhasil diubah');
        }
    }

    public function edit(Request $request)
    {
        session(['sk_uuid' => $request->id]);
        $sk = DB::table('surat_keputusans')
            ->where('id', $request->id)
            ->first();
        session(['org_sk' => $sk->id_organization]);
        session(['detail_org' => $request->detail_org]);
        session(['id_prodi' => $sk->id_prodi]);

        return redirect()->route('surat-keputusan.editForm');
    }

    public function editForm()
    {
        $id_sk = session('sk_uuid');

        $id_organization = session('org_sk');
        $id_prodi = session('id_prodi');

        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id', $id_sk)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.*', 'jenis_surat_keputusans.id as jsk_id', 'jenis_surat_keputusans.jsk_nama')
            ->first();

        $jenis = DB::table('jenis_surat_keputusans')->get();

        return view('surat-keputusan.edit', compact('sk', 'id_organization', 'id_prodi', 'id_sk', 'jenis'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sk_nomor' => 'required|string|max:105|unique:surat_keputusans',
            'sk_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'sk_dokumen' => 'nullable|mimes:pdf|max:10240',
            'id_jenis_surat_keputusan' => 'required|uuid|exists:jenis_surat_keputusans,id',
        ], [
            'sk_nomor.required' => 'Nomor Surat Keputusan harus diisi.',
            'sk_nomor.unique' => 'Nomor Surat Keputusan sudah terdaftar.',
            'sk_nomor.max' => 'Nomor Surat Keputusan maksimal 105 karakter.',
            'sk_tanggal.required' => 'Tanggal Surat Keputusan harus diisi.',
            'sk_tanggal.date' => 'Format tanggal tidak valid.',
            'sk_dokumen.mimes' => 'File Surat Keputusan harus berformat PDF.',
            'sk_dokumen.max' => 'Ukuran File Surat Keputusan maksimal 10MB.',
            'id_jenis_surat_keputusan.required' => 'Jenis Surat Keputusan harus dipilih.',
            'id_jenis_surat_keputusan.uuid' => 'Jenis Surat Keputusan harus berupa UUID.',
            'id_jenis_surat_keputusan.exists' => 'Jenis Surat Keputusan tidak ditemukan.',
        ]);

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->sk_tanggal)->format('Y-m-d');

        DB::beginTransaction();
        try {
            if ($request->hasFile('sk_dokumen')) {
                $filename_surat_keputusan = $this->generateFileName($request->file('sk_dokumen')->getClientOriginalExtension(), 'sk_dokumen', $request->id);

                $request->file('sk_dokumen')->storeAs('dokumen/surat-keputusan/', $filename_surat_keputusan, 'public');

                DB::table('surat_keputusans')
                    ->where('id', $request->id)
                    ->update([
                        'sk_nomor' => $request->sk_nomor,
                        'sk_tanggal' => $formatted_date,
                        'sk_dokumen' => $filename_surat_keputusan,
                        'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                        'id_organization' => $request->id_organization,
                        'id_user' => Auth::user()->id,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('surat_keputusans')
                    ->where('id', $request->id)
                    ->update([
                        'sk_nomor' => $request->sk_nomor,
                        'sk_tanggal' => $formatted_date,
                        'id_jenis_surat_keputusan' => $request->id_jenis_surat_keputusan,
                        'id_organization' => $request->id_organization,
                        'id_user' => Auth::user()->id,
                        'updated_at' => now(),
                    ]);
            }

            Log::info('Surat Keputusan updated ' . $request->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        session(['org_uuid' => $request->id_organization]);

        $sk = DB::table('surat_keputusans')
            ->where('id', $request->id)
            ->first();

        if ($sk->id_prodi != null) {
            return redirect()->route('program-studi.show', ['id' => $sk->id_prodi])->with('success', 'Akreditasi berhasil diubah');
        } else {
            return redirect()->route('perguruan-tinggi.show', ['id' => $sk->id_organization])->with('success', 'Akreditasi berhasil diubah');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->sk_id;

        dd($id);

        DB::beginTransaction();
        try {
            DB::table('surat_keputusans')
                ->where('id', $id)
                ->delete();

            Log::info('Surat Keputusan deleted ' . $id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('surat-keputusan.index');
    }

    public function getSk($id)
    {
        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id_organization', $id)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.*', 'jenis_surat_keputusans.jsk_nama')
            ->get();

        return response()->json($sk);
    }

    public function getDetailSk($id)
    {
        $sk = DB::table('surat_keputusans')
            ->where('surat_keputusans.id', $id)
            ->leftJoin('jenis_surat_keputusans', 'surat_keputusans.id_jenis_surat_keputusan', '=', 'jenis_surat_keputusans.id')
            ->select('surat_keputusans.*', 'jenis_surat_keputusans.jsk_nama')
            ->first();

        return response()->json($sk);
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_sk' => $request->sk_dokumen]);

        return redirect()->route('surat-keputusan.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_sk');
        $path = storage_path('app/public/dokumen/surat-keputusan/' . $filename);

        return response()->file($path);
    }
}
