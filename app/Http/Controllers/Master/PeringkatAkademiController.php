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
