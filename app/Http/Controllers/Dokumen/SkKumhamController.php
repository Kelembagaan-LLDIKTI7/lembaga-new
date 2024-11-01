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
        $akta = Akta::select('id')->findOrFail($id);
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


    public function store(Request $request)
    {
        $request->validate([
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
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

        return redirect()->route('badan-penyelenggara.index')->with('success', 'Data SK Kumham berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $sk_kumham = DB::table('sk_kumhams')
            ->where('id', $id)
            ->first();

        // dd($sk_kumham);

        return view('sk-kumham.edit', compact('sk_kumham'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_akta' => 'required|exists:aktas,id',
            'kumham_nomor' => 'required|string|max:255',
            'kumham_tanggal' => 'required|date',
            'kumham_perihal' => 'required|string|max:255',
            'kumham_dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $skKumham = SkKumham::findOrFail($id);

        $filePath = $skKumham->kumham_dokumen;
        if ($request->hasFile('kumham_dokumen')) {
            $filePath = $request->file('kumham_dokumen')->store('sk_kumham', 'public');
        }

        $skKumham->update([
            'id_akta' => $request->input('id_akta'),
            'kumham_nomor' => $request->input('kumham_nomor'),
            'kumham_tanggal' => $request->input('kumham_tanggal'),
            'kumham_perihal' => $request->input('kumham_perihal'),
            'kumham_dokumen' => $filePath,
        ]);

        return redirect()->route('badan-penyelenggara.index')->with('success', 'Data SK Kumham berhasil diperbarui.');
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_kumham' => $request->kumham_dokumen]);

        return redirect()->route('sk-kumham.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_kumham');
        $path = storage_path('app/public/dokumen/kumham/' . $filename);

        return response()->file($path);
    }
}
