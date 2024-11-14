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
        $request->validate([
            'peringkat_nama' => 'required|string',
            'peringkat_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['peringkat_status'] = 'active';

        if ($request->hasFile('peringkat_logo')) {
            $data['peringkat_logo'] = $request->file('peringkat_logo')->store('peringkat_akademi');
        }

        PeringkatAkreditasi::create($data);
        return redirect()->route('peringkat-akademik.index')->with('success', 'Peringkat Akademi created successfully.');
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
            'peringkat_status' => 'required|string|in:active,inactive', // Validate status
        ]);

        $peringkat = PeringkatAkreditasi::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('peringkat_logo')) {
            // Delete old logo if exists
            if ($peringkat->peringkat_logo) {
                \Storage::delete($peringkat->peringkat_logo);
            }
            $data['peringkat_logo'] = $request->file('peringkat_logo')->store('peringkat_akademi');
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
