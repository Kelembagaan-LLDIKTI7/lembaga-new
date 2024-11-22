<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\PimpinanOrganisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PimpinanOrganisasiController extends Controller
{
    public function create($id)
    {
        $jabatan = DB::table('jabatans')->orderBy('jabatan_nama', 'asc')->get();
        $org = DB::table('organisasis')->get();

        return view('Pimpinan-Organisasi.Create', compact('jabatan', 'org', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pimpinan_nama' => 'required|string|max:100',
            'pimpinan_email' => 'required|email|max:100|unique:pimpinan_organisasis,pimpinan_email',
            'pimpinan_tanggal' => 'required',
            'pimpinan_sk' => 'required|string|max:45|unique:pimpinan_organisasis,pimpinan_sk',
            'pimpinan_sk_dokumen' => 'nullable|mimes:pdf|max:2048',
            'pimpinan_status' => 'required|in:Aktif,Tidak Aktif',
            'id_jabatan' => 'required|uuid|exists:jabatans,id',
            'id_organization' => 'required|uuid|exists:organisasis,id',
        ], [
            'pimpinan_nama.required' => 'Nama Pimpinan harus diisi.',
            'pimpinan_nama.max' => 'Nama Pimpinan maksimal 100 karakter.',
            'pimpinan_email.required' => 'Email Pimpinan harus diisi.',
            'pimpinan_email.email' => 'Email Pimpinan tidak valid.',
            'pimpinan_email.max' => 'Email Pimpinan maksimal 100 karakter.',
            'pimpinan_email.unique' => 'Email Pimpinan sudah terdaftar.',
            'pimpinan_tanggal.required' => 'Tanggal Pelantikan Pimpinan harus diisi.',
            'pimpinan_sk.required' => 'Nomor SK Pimpinan harus diisi.',
            'pimpinan_sk.max' => 'Nomor SK Pimpinan maksimal 45 karakter.',
            'pimpinan_sk.unique' => 'Nomor SK Pimpinan sudah terdaftar.',
            'pimpinan_sk_dokumen.mimes' => 'File SK Pimpinan harus berformat PDF.',
            'pimpinan_sk_dokumen.max' => 'Ukuran File SK Pimpinan maksimal 2MB.',
            'pimpinan_status.required' => 'Status Pimpinan harus dipilih.',
            'id_jabatan.required' => 'Jabatan Pimpinan harus diisi.',
            'id_jabatan.uuid' => 'Jabatan Pimpinan harus berupa UUID.',
            'id_organization.required' => 'Perguruan Tinggi harus diisi.',
            'id_organization.uuid' => 'Perguruan Tinggi harus berupa UUID.',
        ]);

        $id_pimpinan = Str::uuid()->toString();
        if ($request->hasFile('pimpinan_sk_dokumen')) {
            $filename_sk = $this->generateFileName($request->file('pimpinan_sk_dokumen')->getClientOriginalExtension(), 'pimpinan_sk_dokumen', $id_pimpinan);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('pimpinan_sk_dokumen')) {
                DB::table('pimpinan_organisasis')->insert([
                    'id' => $id_pimpinan,
                    'pimpinan_nama' => $request->pimpinan_nama,
                    'pimpinan_email' => $request->pimpinan_email,
                    'pimpinan_tanggal' => $request->pimpinan_tanggal,
                    'pimpinan_sk' => $request->pimpinan_sk,
                    'pimpinan_sk_dokumen' => $filename_sk,
                    'pimpinan_status' => $request->pimpinan_status,
                    'id_jabatan' => $request->id_jabatan,
                    'id_organization' => $request->id_organization,
                ]);

                $request->file('pimpinan_sk_dokumen')->storeAs('dokumen/pimpinan_organisasi/', $filename_sk, 'public');
            } else {
                DB::table('pimpinan_organisasis')->insert([
                    'id' => $id_pimpinan,
                    'pimpinan_nama' => $request->pimpinan_nama,
                    'pimpinan_email' => $request->pimpinan_email,
                    'pimpinan_tanggal' => $request->pimpinan_tanggal,
                    'pimpinan_sk' => $request->pimpinan_sk,
                    'pimpinan_status' => $request->pimpinan_status,
                    'id_jabatan' => $request->id_jabatan,
                    'id_organization' => $request->id_organization,
                ]);
            }


            Log::info('Pimpinan Organisasi created ' . $id_pimpinan);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return redirect()->back()->with('failed', 'Data Gagal Ditambahkan');
        }

        $checkOrg = DB::table('organisasis')
            ->where('id', $request->id_organization)
            ->first();

        if ($checkOrg->organisasi_type_id == 2) {
            return redirect()->route('badan-penyelenggara.show', ['id' => $request->id_organization]);
        } elseif ($checkOrg->organisasi_type_id == 3) {
            return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_organization]);
        }
    }

    public function edit($id)
    {
        $pimpinan = DB::table('pimpinan_organisasis')
            ->where('id', $id)
            ->first();

        $jabatan = DB::table('jabatans')->get();

        $id_organization = session('org_uuid');

        return view('pimpinan_organisasi.edit', compact('pimpinan', 'jabatan', 'id_organization'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pimpinan_nama' => 'required|string|max:100',
            'pimpinan_email' => 'required|email|max:100',
            'pimpinan_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'pimpinan_sk' => 'required|string|max:45',
            'pimpinan_sk_dokumen' => 'nullable|mimes:pdf|max:2048',
            'pimpinan_status' => 'required|in:Aktif,Tidak Aktif',
            'id_jabatan' => 'required|uuid|exists:jabatans,id',
            'id_organization' => 'required|uuid|exists:organisasis,id',
        ], [
            'pimpinan_nama.required' => 'Nama Pimpinan harus diisi.',
            'pimpinan_nama.max' => 'Nama Pimpinan maksimal 100 karakter.',
            'pimpinan_email.required' => 'Email Pimpinan harus diisi.',
            'pimpinan_email.email' => 'Email Pimpinan tidak valid.',
            'pimpinan_email.max' => 'Email Pimpinan maksimal 100 karakter.',
            'pimpinan_tanggal.required' => 'Tanggal Pelantikan Pimpinan harus diisi.',
            'pimpinan_sk.required' => 'Nomor SK Pimpinan harus diisi.',
            'pimpinan_sk.max' => 'Nomor SK Pimpinan maksimal 45 karakter.',
            'pimpinan_sk_dokumen.mimes' => 'File SK Pimpinan harus berformat PDF.',
            'pimpinan_sk_dokumen.max' => 'Ukuran File SK Pimpinan maksimal 2MB.',
            'pimpinan_status.required' => 'Status Pimpinan harus dipilih.',
            'id_jabatan.required' => 'Jabatan Pimpinan harus diisi.',
            'id_jabatan.uuid' => 'Jabatan Pimpinan harus berupa UUID.',
            'id_organization.required' => 'Perguruan Tinggi harus diisi.',
            'id_organization.uuid' => 'Perguruan Tinggi harus berupa UUID.',
        ]);

        DB::beginTransaction();

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->pimpinan_tanggal)->format('Y-m-d');

        try {
            if ($request->hasFile('pimpinan_sk_dokumen')) {
                $filename_sk = $this->generateFileName($request->file('pimpinan_sk_dokumen')->getClientOriginalExtension(), 'pimpinan_sk_dokumen', $request->id);

                $request->file('pimpinan_sk_dokumen')->storeAs('dokumen/pimpinan_organisasi/', $filename_sk, 'public');


                DB::table('pimpinan_organisasis')
                    ->where('id', $request->id)
                    ->update([
                        'pimpinan_nama' => $request->pimpinan_nama,
                        'pimpinan_email' => $request->pimpinan_email,
                        'pimpinan_tanggal' => $formatted_date,
                        'pimpinan_sk' => $request->pimpinan_sk,
                        'pimpinan_sk_dokumen' => $filename_sk,
                        'pimpinan_status' => $request->pimpinan_status,
                        'id_jabatan' => $request->id_jabatan,
                        'id_organization' => $request->id_organization,
                    ]);
            } else {
                DB::table('pimpinan_organisasis')
                    ->where('id', $request->id)
                    ->update([
                        'pimpinan_nama' => $request->pimpinan_nama,
                        'pimpinan_email' => $request->pimpinan_email,
                        'pimpinan_tanggal' => $formatted_date,
                        'pimpinan_sk' => $request->pimpinan_sk,
                        'pimpinan_status' => $request->pimpinan_status,
                        'id_jabatan' => $request->id_jabatan,
                        'id_organization' => $request->id_organization,
                    ]);
            }

            Log::info('Pimpinan Organisasi updated ' . $request->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('failed', 'Data Gagal Diubah');
        }

        $checkOrg = DB::table('organisasis')
            ->where('id', $request->id_organization)
            ->first();

        if ($checkOrg->org_type_id == 2) {
            return redirect()->route('badan-penyelenggara.show', ['id' => $request->id_organization]);
        } elseif ($checkOrg->org_type_id == 3) {
            return redirect()->route('perguruan-tinggi.show', ['id' => $request->id_organization]);
        }
    }

    public function destroy(Request $request)
    {
        $data = DB::table('pimpinan_organisasis')
            ->where('id', $request->id)
            ->first();

        DB::beginTransaction();
        try {
            if ($data->pimpinan_sk_dokumen) {
                Storage::disk('public')->delete('dokumen/pimpinan_organisasi/' . $data->pimpinan_sk_dokumen);
            }

            DB::table('pimpinan_organisasis')
                ->where('id', $request->id)
                ->delete();

            Log::info('Pimpinan Organisasi deleted ' . $request->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('failed', 'Data Gagal Dihapus');
        }

        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function getDetailPimpinan($id)
    {
        $pimpinan = DB::table('pimpinan_organisasis')
            ->where('pimpinan_organisasis.id', $id)
            ->join('jabatans', 'pimpinan_organisasis.id_jabatan', '=', 'jabatans.id')
            ->select('pimpinan_organisasis.*', 'jabatans.jabatan_nama')
            ->first();

        return response()->json($pimpinan);
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_sk' => $request->pimpinan_sk_dokumen]);

        return redirect()->route('pimpinan-organisasi.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_sk');
        $path = storage_path('app/public/dokumen/surat-keputusan/' . $filename);

        return response()->file($path);
    }
}
