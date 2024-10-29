<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\OrganisasiType;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
