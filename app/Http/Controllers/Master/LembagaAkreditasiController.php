<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\LembagaAkreditasi;
use Illuminate\Http\Request;

class LembagaAkreditasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lembagas = LembagaAkreditasi::selectRaw(
            'id, lembaga_nama, lembaga_nama_singkat, lembaga_status, lembaga_logo'
        )
            ->orderBy('lembaga_nama', 'asc')
            ->get();
        return view('Master.LembagaAkreditasi.Index', ['lembagas' => $lembagas]);
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
        $request->validate([
            'lembaga_nama' => 'required|string',
            'lembaga_nama_singkat' => 'required|string',
            'lembaga_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lembaga_status' => 'required|string',
        ]);

        $data = $request->all();
        if (!isset($data['lembaga_status'])) {
            $data['lembaga_status'] = 'aktif';
        }

        if ($request->hasFile('lembaga_logo')) {
            $file = $request->file('lembaga_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lembaga_akreditasi', $filename, 'public');
            $data['lembaga_logo'] = $filename;
        }

        LembagaAkreditasi::create($data);
        return redirect()->route('lembaga-akademik.index');
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
        $lembaga = LembagaAkreditasi::findOrFail($id);
        return view('Master.LembagaAkreditasi.Edit', compact('lembaga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'lembaga_nama' => 'required|string',
            'lembaga_nama_singkat' => 'required|string',
            'lembaga_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lembaga_status' => 'required|string',
        ]);

        $lembaga = LembagaAkreditasi::findOrFail($id);

        $data = $request->only(['lembaga_nama', 'lembaga_nama_singkat', 'lembaga_status']);

        if ($request->hasFile('lembaga_logo')) {
            if ($lembaga->lembaga_logo && \Storage::disk('public')->exists('lembaga_akreditasi/' . $lembaga->lembaga_logo)) {
                \Storage::disk('public')->delete('lembaga_akreditasi/' . $lembaga->lembaga_logo);
            }

            $file = $request->file('lembaga_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lembaga_akreditasi', $filename, 'public');
            $data['lembaga_logo'] = $filename;
        }

        $lembaga->update($data);

        return redirect()->route('lembaga-akademik.index')->with('success', 'Lembaga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lembaga = LembagaAkreditasi::findOrFail($id);
        if ($lembaga->lembaga_logo) {
            \Storage::delete($lembaga->lembaga_logo);
        }
        $lembaga->delete();
        return redirect()->route('lembaga-akademik.index')->with('success', 'Lembaga deleted successfully.');
    }
}
