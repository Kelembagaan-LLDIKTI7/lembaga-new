<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\BentukPt;
use App\Models\OrganisasiType;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::selectRaw(
            'id, jabatan_nama, jabatan_status, jabatan_organisasi, bentuk_pt'
        )
            ->orderBy('jabatan_nama', 'asc')
            ->get();

        $organisasi_types = OrganisasiType::pluck('organisasi_type_nama', 'id');
        $bentuk_pts = BentukPt::pluck('bentuk_nama', 'id');

        return view('Master.Jabatan.Index', compact('jabatans', 'organisasi_types', 'bentuk_pts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organisasi_types = OrganisasiType::pluck('organisasi_type_nama', 'id');
        $bentuk_pts = BentukPt::pluck('bentuk_nama', 'id');
        return view('jabatan.create', compact('organisasi_types', 'bentuk_pts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jabatan_nama' => 'required|string',
            'jabatan_organisasi' => 'required|exists:organisasi_types,id',
            'bentuk_pt' => 'required|exists:bentuk_pts,id'
        ]);

        $dd = $request->all();
        Jabatan::create($dd);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan!');
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
        $jabatan = Jabatan::findOrFail($id);
        $organisasi_types = OrganisasiType::pluck('organisasi_type_nama', 'id');
        $bentuk_pts = BentukPt::pluck('bentuk_nama', 'id');
        return view('Master.Jabatan.Edit', compact('jabatan', 'organisasi_types', 'bentuk_pts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jabatan_nama' => 'required|string',
            'jabatan_organisasi' => 'required|exists:organisasi_types,id',
            'bentuk_pt' => 'required|exists:bentuk_pts,id'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan deleted successfully.');
    }
}
