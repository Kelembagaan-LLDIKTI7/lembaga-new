<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Akta;
use App\Models\SkKumham;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SkKumhamController extends Controller
{
    public function createSession(Request $request)
    {
        session(['akta_uuid' => $request->id]);

        $akta = DB::table('aktas')
            ->where('aktas.id', $request->id)
            ->leftJoin('sk_kumhams', 'aktas.id', '=', 'sk_kumhams.id_akta')
            ->select('aktas.*', 'sk_kumhams.id as kumham_id', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_perihal', 'sk_kumhams.kumham_tanggal', 'sk_kumhams.kumham_dokumen')
            ->first();

        session(['org_akta' => $akta->id_organization]);

        if ($akta->kumham_id) {
            return redirect()->route('sk-kumham.edit', ['id' => $akta->kumham_id]);
        }

        return redirect()->route('sk-kumham.create');
    }

    public function create($id)
    {
        $akta = Akta::select('id', 'id_organization')->findOrFail($id);
        $skKumham = SkKumham::where('id_akta', $id)->first();

        if ($skKumham) {
            return view('Dokumen.SkKumham.Edit', [
                'akta' => $akta,
                'skKumham' => $skKumham,
            ]);
        }

        return view('Dokumen.SkKumham.Create', [
            'akta' => $akta,
        ]);
    }

    public function validationStore(Request $request)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'kumham_nomor.required' => 'Nomor harus diisi.',
            'kumham_nomor.max' => 'Nomor tidak boleh lebih dari 255 karakter.',
            'kumham_tanggal.required' => 'Tanggal harus diisi.',
            'kumham_tanggal.date' => 'Tanggal harus valid.',
            'kumham_perihal.required' => 'Perihal harus diisi.',
            'kumham_perihal.max' => 'Perihal tidak boleh lebih dari 255 karakter.',
            'kumham_dokumen.required' => 'Dokumen harus diisi.',
            'kumham_dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, atau DOCX.',
            'kumham_dokumen.max' => 'Dokumen tidak boleh lebih dari 2MB.',
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

    public function store(Request $request)
    {
        $request->validate([
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'kumham_nomor.required' => 'Nomor harus diisi.',
            'kumham_nomor.max' => 'Nomor tidak boleh lebih dari 255 karakter.',
            'kumham_tanggal.required' => 'Tanggal harus diisi.',
            'kumham_tanggal.date' => 'Tanggal harus valid.',
            'kumham_perihal.required' => 'Perihal harus diisi.',
            'kumham_perihal.max' => 'Perihal tidak boleh lebih dari 255 karakter.',
            'kumham_dokumen.required' => 'Dokumen harus diisi.',
            'kumham_dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, atau DOCX.',
            'kumham_dokumen.max' => 'Dokumen tidak boleh lebih dari 2MB.',
        ]);

        if ($request->hasFile('kumham_dokumen')) {
            $filePath = $request->file('kumham_dokumen')->store('sk_kumham', 'public');
        }

        SkKumham::create([
            'id_akta' => $request->id_akta,
            'kumham_nomor' => $request->kumham_nomor,
            'kumham_tanggal' => $request->kumham_tanggal,
            'kumham_perihal' => $request->kumham_perihal,
            'kumham_dokumen' => $filePath ?? null,
        ]);

        $akta =  Akta::select('id_organization')->where('id', $request->id_akta)->first();

        session()->flash('success', 'Data SK Kumham berhasil disimpan.');

        return response()->json([
            'success' => true,
            'redirect' => route('badan-penyelenggara.show', ['id' => $akta->id_organization]),
        ]);
    }

    public function edit(string $id)
    {
        $skKumham = DB::table('sk_kumhams')
            ->where('id_akta', $id)
            ->first();

        $akta = DB::table('aktas')
            ->where('id', $id)
            ->first();

        // dd($skKumham);

        return view('Dokumen.SkKumham.Edit', compact('skKumham', 'akta'));
    }

    public function validationUpdate(Request $request, string $id)
    {
        // Validasi data input
        $validator = \Validator::make($request->all(), [
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'kumham_nomor.required' => 'Nomor harus diisi.',
            'kumham_nomor.max' => 'Nomor tidak boleh lebih dari 255 karakter.',
            'kumham_tanggal.required' => 'Tanggal harus diisi.',
            'kumham_tanggal.date' => 'Tanggal harus valid.',
            'kumham_perihal.required' => 'Perihal harus diisi.',
            'kumham_perihal.max' => 'Perihal tidak boleh lebih dari 255 karakter.',
            'kumham_dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, atau DOCX.',
            'kumham_dokumen.max' => 'Dokumen tidak boleh lebih dari 2MB.',
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'kumham_nomor.required' => 'Nomor harus diisi.',
            'kumham_nomor.max' => 'Nomor tidak boleh lebih dari 255 karakter.',
            'kumham_tanggal.required' => 'Tanggal harus diisi.',
            'kumham_tanggal.date' => 'Tanggal harus valid.',
            'kumham_perihal.required' => 'Perihal harus diisi.',
            'kumham_perihal.max' => 'Perihal tidak boleh lebih dari 255 karakter.',
            'kumham_dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, atau DOCX.',
            'kumham_dokumen.max' => 'Dokumen tidak boleh lebih dari 2MB.',
        ]);

        $skKumham = SkKumham::findOrFail($id);

        $filePath = $skKumham->kumham_dokumen;
        if ($request->hasFile('kumham_dokumen')) {
            $filePath = $request->file('kumham_dokumen')->store('sk_kumham', 'public');
            $skKumham->update([
                'id_akta' => $request->input('id_akta'),
                'kumham_nomor' => $request->input('kumham_nomor'),
                'kumham_tanggal' => $request->input('kumham_tanggal'),
                'kumham_perihal' => $request->input('kumham_perihal'),
                'kumham_dokumen' => $filePath,
            ]);
        } else {
            $skKumham->update([
                'id_akta' => $request->input('id_akta'),
                'kumham_nomor' => $request->input('kumham_nomor'),
                'kumham_tanggal' => $request->input('kumham_tanggal'),
                'kumham_perihal' => $request->input('kumham_perihal'),
            ]);
        }


        $akta =  Akta::select('id_organization')->where('id', $request->id_akta)->first();

        session()->flash('success', 'Data SK Kumham berhasil diperbarui.');

        return response()->json([
            'success' => true,
            'redirect' => route('badan-penyelenggara.show', ['id' => $akta->id_organization]),
        ]);
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_kumham' => $request->kumham_dokumen]);

        return redirect()->route('sk-kumham.viewPdf');
    }

    public function viewPdf(Request $request)
    {
        return response()->file(storage_path('app/public/' . $request->kumham_dokumen));
    }
}
