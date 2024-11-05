<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Skbp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkbpController extends Controller
{
    public function create($id)
    {
        return view('Dokumen.Skbp.Create', compact('id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:35',
            'tanggal' => 'required|date',
            'jenis' => 'required|string|max:9',
            'id_organization' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nomor.required' => 'Nomor SKBP wajib diisi',
            'nomor.max' => 'Nomor SKBP tidak boleh lebih dari 35 karakter',
            'tanggal.required' => 'Tanggal SKBP wajib diisi',
            'jenis.required' => 'Jenis SKBP wajib diisi',
            'jenis.max' => 'Jenis SKBP tidak boleh lebih dari 9 karakter',
            'id_organization.required' => 'Organisasi wajib diisi',
            'dokumen.mimes' => 'File harus berupa PDF',
            'dokumen.max' => 'File tidak boleh lebih dari 2 MB',
        ]);

        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen')->store('skbp', 'public');
            DB::table('skbps')->insert([
                'nomor' => $request->input('nomor'),
                'tanggal' => $request->input('tanggal'),
                'jenis' => $request->input('jenis'),
                'id_organization' => $request->input('id_organization'),
                'dokumen' => $dokumen,
                'created_at' => now(),
                'id_user' => Auth::user()->id
            ]);
        } else {
            DB::table('skbps')->insert([
                'nomor' => $request->input('nomor'),
                'tanggal' => $request->input('tanggal'),
                'jenis' => $request->input('jenis'),
                'id_organization' => $request->input('id_organization'),
                'created_at' => now(),
                'id_user' => Auth::user()->id
            ]);
        }

        return redirect()->route('badan-penyelenggara.show', ['id' => $request->input('id_organization')]);
    }

    public function edit($id)
    {
        $skbp = DB::table('skbps')->where('id', $id)->first();
        return view('Dokumen.Skbp.Edit', compact('skbp'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor' => 'required|string|max:45',
            'tanggal' => 'required|date',
            'jenis' => 'required|string|max:9',
            'id_organization' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen')->store('skbp', 'public');
            DB::table('skbps')->where('id', $id)->update([
                'nomor' => $request->input('nomor'),
                'tanggal' => $request->input('tanggal'),
                'jenis' => $request->input('jenis'),
                'id_organization' => $request->input('id_organization'),
                'dokumen' => $dokumen,
                'updated_at' => now(),
                'id_user' => Auth::user()->id
            ]);
        } else {
            DB::table('skbps')->where('id', $id)->update([
                'nomor' => $request->input('nomor'),
                'tanggal' => $request->input('tanggal'),
                'jenis' => $request->input('jenis'),
                'id_organization' => $request->input('id_organization'),
                'updated_at' => now(),
                'id_user' => Auth::user()->id
            ]);
        }

        return redirect()->route('badan-penyelenggara.show', ['id' => $request->input('id_organization')]);
    }

    public function viewPdf($id)
    {
        $skbp = DB::table('skbps')->where('id', $id)->first();
        if (!$skbp || !$skbp->dokumen) {
            return abort(404);
        }
        return response()->file(storage_path('app/public/' . $skbp->dokumen));
    }
}
