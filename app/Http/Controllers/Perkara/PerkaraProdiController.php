<?php

namespace App\Http\Controllers\Perkara;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class PerkaraProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $prodi = ProgramStudi::select('id')->findOrFail($id);
        return view('Perkara.Prodi.Create', ['prodi' => $prodi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fileNames = [];

        if ($request->hasFile('bukti_foto')) {
            foreach ($request->file('bukti_foto') as $file) {
                $fileName = $file->store('bukti_foto', 'public');
                $fileNames[] = basename($fileName);
            }
        }

        Perkara::create([
            'title' => $request->title,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'deskripsi_kejadian' => $request->deskripsi_kejadian,
            'bukti_foto' => json_encode($fileNames),
            'id_prodi' => $request->id_prodi,
            'status' => 'Diunggah'
        ]);

        return redirect()->route('program-studi.show', $request->id_prodi)->with('success', 'Perkara berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perkaras = Perkara::where('id', $id)
            ->select('id', 'title', 'tanggal_kejadian', 'status', 'deskripsi_kejadian', 'bukti_foto', 'id_prodi')
            ->with([
                'prodi' => function ($query) {
                    $query->select(
                        'id',
                        'prodi_nama',
                        'prodi_jenjang',
                        'prodi_active_status',
                    );
                }
            ])->first();
        return view('Perkara.Prodi.Show', ['perkaras' => $perkaras]);
        // return response()->json(['perkaras' => $perkaras]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $perkara = Perkara::findOrFail($id);

        $perkara->status = $request->input('status');
        $perkara->save();
        
        return redirect()->back()->with('success', 'Status perkara berhasil diperbarui.');
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
