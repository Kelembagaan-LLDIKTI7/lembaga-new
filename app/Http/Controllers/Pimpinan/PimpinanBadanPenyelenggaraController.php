<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Organisasi;
use App\Models\PimpinanOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PimpinanBadanPenyelenggaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        // dd($id);
        $bp = Organisasi::select('id')->findOrFail($id);
        $jabatan = Jabatan::select('id', 'jabatan_nama', 'jabatan_status', 'jabatan_organisasi')
            ->where('bentuk_pt', null)
            ->orderBy('jabatan_nama', 'asc')
            ->get();

        return view('Pimpinan.BadanPenyelenggara.Create', [
            'bp' => $bp,
            'jabatan' => $jabatan
        ]);
    }

    public function validationStore(Request $request)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'pimpinan_nama' => 'nullable|string|max:255',
            'pimpinan_email' => 'nullable|email|max:255',
            'pimpinan_sk' => 'nullable|string|max:255',
            'pimpinan_tanggal' => 'nullable|date',
            'pimpinan_jabatan' => 'nullable|string|max:255',
            'pimpinan_sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'pimpinan_nama.max' => 'Nama Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_email.email' => 'Email Pimpinan harus valid',
            'pimpinan_email.max' => 'Email Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk.max' => 'SK Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_jabatan.max' => 'Jabatan Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX',
            'pimpinan_sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2 MB',
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
        $request->validate([
            'pimpinan_nama' => 'nullable|string|max:255',
            'pimpinan_email' => 'nullable|email|max:255',
            'pimpinan_sk' => 'nullable|string|max:255',
            'pimpinan_tanggal' => 'nullable|date',
            'pimpinan_jabatan' => 'nullable|string|max:255',
            'pimpinan_sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'pimpinan_nama.max' => 'Nama Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_email.email' => 'Email Pimpinan harus valid',
            'pimpinan_email.max' => 'Email Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk.max' => 'SK Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_jabatan.max' => 'Jabatan Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX',
            'pimpinan_sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2 MB',
        ]);

        $filePath = null;
        if ($request->hasFile('pimpinan_sk_dokumen')) {
            $filePath = $request->file('pimpinan_sk_dokumen')->store('dokumen_sk', 'public');
        }

        PimpinanOrganisasi::create([
            'id_organization' => $request->id_organization,
            'pimpinan_nama' => $request->pimpinan_nama,
            'pimpinan_email' => $request->pimpinan_email,
            'pimpinan_sk' => $request->pimpinan_sk,
            'pimpinan_tanggal' => $request->pimpinan_tanggal,
            'pimpinan_jabatan' => $request->pimmpinan_jabatan,
            'pimpinan_sk_dokumen' => $filePath,
            'pimpinan_status' => 'Berlaku',
        ]);

        session()->flash('success', 'Data pimpinan berhasil ditambahkan.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('badan-penyelenggara.show', ['id' => $request->id_organization]),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pimpinan = DB::table('pimpinan_organisasis')
            ->where('pimpinan_organisasis.id', $id)
            ->leftJoin('jabatans', 'pimpinan_organisasis.id_jabatan', '=', 'jabatans.id')
            ->select('pimpinan_organisasis.*', 'jabatans.jabatan_nama')
            ->first();

        return response()->json($pimpinan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pimpinan = PimpinanOrganisasi::findOrFail($id);
        $jabatan = Jabatan::select('id', 'jabatan_nama')
            ->where('bentuk_pt', null)
            ->orderBy('jabatan_nama', 'asc')
            ->get();

        return view('Pimpinan.BadanPenyelenggara.Edit', [
            'pimpinan' => $pimpinan,
            'jabatan' => $jabatan
        ]);
    }

    public function validationUpdate(Request $request, string $id)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'pimpinan_nama' => 'nullable|string|max:255',
            'pimpinan_email' => 'nullable|email|max:255',
            'pimpinan_sk' => 'nullable|string|max:255',
            'pimpinan_status' => 'nullable|string|max:255',
            'pimpinan_tanggal' => 'nullable|date',
            'pimpinan_jabatan' => 'nullable|string|max:255',
            'pimpinan_sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'pimpinan_nama.max' => 'Nama Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_email.email' => 'Email Pimpinan harus valid',
            'pimpinan_email.max' => 'Email Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk.max' => 'SK Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_status.max' => 'Status Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX',
            'pimpinan_sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2 MB',
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
        // dd($request->all());
        $request->validate([
            'pimpinan_nama' => 'nullable|string|max:255',
            'pimpinan_email' => 'nullable|email|max:255',
            'pimpinan_sk' => 'nullable|string|max:255',
            'pimpinan_status' => 'nullable|string|max:255',
            'pimpinan_tanggal' => 'nullable|date',
            'pimpinan_jabatan' => 'nullable|string|max:255',
            'pimpinan_sk_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'pimpinan_nama.max' => 'Nama Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_email.email' => 'Email Pimpinan harus valid',
            'pimpinan_email.max' => 'Email Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk.max' => 'SK Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_status.max' => 'Status Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_jabatan.max' => 'Jabatan Pimpinan tidak boleh lebih dari 255 karakter',
            'pimpinan_sk_dokumen.mimes' => 'Dokumen SK harus berupa PDF, DOC, atau DOCX',
            'pimpinan_sk_dokumen.max' => 'Dokumen SK tidak boleh lebih dari 2 MB',
        ]);

        $filePath = null;

        if ($request->hasFile('pimpinan_sk_dokumen')) {
            $filePath = $request->file('pimpinan_sk_dokumen')->store('dokumen_sk', 'public');
            PimpinanOrganisasi::where('id', $id)->update([
                'id_organization' => $request->id_organization,
                'pimpinan_nama' => $request->pimpinan_nama,
                'pimpinan_email' => $request->pimpinan_email,
                'pimpinan_sk' => $request->pimpinan_sk,
                'pimpinan_tanggal' => $request->pimpinan_tanggal,
                'pimpinan_jabatan' => $request->pimpinan_jabatan,
                'pimpinan_sk_dokumen' => $filePath,
                'pimpinan_status' => $request->pimpinan_status,
            ]);
        } else {
            PimpinanOrganisasi::where('id', $id)->update([
                'id_organization' => $request->id_organization,
                'pimpinan_nama' => $request->pimpinan_nama,
                'pimpinan_email' => $request->pimpinan_email,
                'pimpinan_sk' => $request->pimpinan_sk,
                'pimpinan_tanggal' => $request->pimpinan_tanggal,
                'pimpinan_jabatan' => $request->pimpinan_jabatan,
                'pimpinan_status' => $request->pimpinan_status,
            ]);
        }

        session()->flash('success', 'Data pimpinan berhasil diubah.');

        return response()->json([
            'success' => true,
            'redirect_url' => route('badan-penyelenggara.show', ['id' => $request->id_organization]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function viewPdf(Request $request)
    {
        if (!$request->pimpinan_sk_dokumen) {
            return abort(404);
        }
        return response()->file(storage_path('app/public/' . $request->pimpinan_sk_dokumen));
    }
}
