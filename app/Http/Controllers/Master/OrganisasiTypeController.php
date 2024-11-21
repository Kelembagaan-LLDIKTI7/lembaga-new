<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\OrganisasiType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisasiTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organisasiTypes = OrganisasiType::selectRaw(
            'id, organisasi_type_nama'
        )
            ->orderBy('organisasi_type_nama', 'asc')
            ->get();
        return view('Master.OrganisasiJenis.Index', ['organisasiTypes' => $organisasiTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is not needed since we are using modals in the index view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'organisasi_type_nama' => 'required|string|max:255',
        ]);

        OrganisasiType::create($request->all());
        return redirect()->route('organisasi-type.index')->with('success', 'Organisasi Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // This method can be implemented if needed for viewing a single resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // This method is not needed since we are using modals in the index view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'organisasi_type_nama' => 'required|string|max:255',
        ]);

        $organisasiType = OrganisasiType::findOrFail($id);
        $organisasiType->update($request->all());
        return redirect()->route('organisasi-type.index')->with('success', 'Organisasi Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('organisasi_types')->where('id', $id)->delete();
        return redirect()->route('organisasi-type.index')->with('success', 'Organisasi Type deleted successfully.');
    }
}
