<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PeringkatAkreditasi;
use Illuminate\Http\Request;

class PeringkatAkademiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peringkats = PeringkatAkreditasi::selectRaw(
            'id, peringkat_nama, peringkat_logo, peringkat_status'
        )
            ->orderBy('peringkat_nama', 'asc')
            ->get();
        return view('Master.PeringkatAkreditasi.Index', ['peringkats' => $peringkats]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'peringkat_nama' => 'required|string',
            'peringkat_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('peringkat_logo')) {
            $file = $request->file('peringkat_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('peringkat_akreditasi', $filename, 'public');
            $validated['peringkat_logo'] = $filename;
        }

        $validated['peringkat_status'] = 'Aktif';

        PeringkatAkreditasi::create($validated);

        return redirect()->route('peringkat-akademik.index')->with('success', 'Peringkat Akreditasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peringkat = PeringkatAkreditasi::findOrFail($id);
        return view('Master.PeringkatAkreditasi.Edit', compact('peringkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'peringkat_nama' => 'required|string',
            'peringkat_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'peringkat_status' => 'required|string|in:Aktif,Tidak Aktif',
        ]);

        $peringkat = PeringkatAkreditasi::findOrFail($id);

        $data = $request->only(['peringkat_nama', 'peringkat_status']);

        if ($request->hasFile('peringkat_logo')) {
            if ($peringkat->peringkat_logo && \Storage::disk('public')->exists('peringkat_akreditasi/' . $peringkat->peringkat_logo)) {
                \Storage::disk('public')->delete('peringkat_akreditasi/' . $peringkat->peringkat_logo);
            }

            $file = $request->file('peringkat_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('peringkat_akreditasi', $filename, 'public');
            $data['peringkat_logo'] = $filename;
        }

        $peringkat->update($data);

        return redirect()->route('peringkat-akademik.index')->with('success', 'Peringkat Akademi updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peringkat = PeringkatAkreditasi::findOrFail($id);
        // Delete logo if exists
        if ($peringkat->peringkat_logo) {
            \Storage::delete($peringkat->peringkat_logo);
        }
        $peringkat->delete();
        return redirect()->route('peringkat-akademik.index')->with('success', 'Peringkat Akademi deleted successfully.');
    }
}
