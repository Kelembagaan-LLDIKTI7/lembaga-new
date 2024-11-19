<?php

namespace App\Http\Controllers\Perkara;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Organisasi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perkarasOrg = Perkara::with(['organisasi:id,organisasi_nama'])->whereNotNull('id_organization')->get();
        $perkarasProdi = Perkara::select('perkaras.*', 'organisasis.organisasi_nama', 'program_studis.prodi_nama')
            ->join('program_studis', 'perkaras.id_prodi', '=', 'program_studis.id')
            ->join('organisasis', 'program_studis.id_organization', '=', 'organisasis.id')
            ->whereNotNull('perkaras.id_prodi')
            ->get();


        return view('Perkara.All.Index', ['perkarasOrg' => $perkarasOrg, 'perkarasProdi' => $perkarasProdi]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function validationUpdate(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Judul perkara harus diisi.',
            'title.max' => 'Judul perkara maksimal 255 karakter.',
            'tanggal_kejadian.required' => 'Tanggal kejadian harus diisi.',
            'tanggal_kejadian.date' => 'Tanggal kejadian harus berupa tanggal.',
            'deskripsi_kejadian.required' => 'Deskripsi kejadian harus diisi.',
            'bukti_foto.image' => 'Bukti foto harus berupa gambar.',
            'bukti_foto.mimes' => 'Bukti foto harus berformat JPEG, PNG, JPG, atau GIF.',
            'bukti_foto.max' => 'Bukti foto maksimal 2048 KB.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
    
        return response()->json([
            'success' => true,
        ]);
    }    

    public function edit(string $id)
    {
        $perkara = Perkara::findOrFail($id);
        $existingImages = json_decode($perkara->bukti_foto, true) ?? [];

        $organisasi = Organisasi::select('id', 'organisasi_nama')->get();
        $prodi = ProgramStudi::select('id', 'prodi_nama')->get();

        return view('Perkara.All.Edit', [
            'perkara' => $perkara,
            'organisasi' => $organisasi,
            'prodi' => $prodi,
            'existingImages' => $existingImages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $perkara = Perkara::findOrFail($id);
    
        $fileNames = json_decode($perkara->bukti_foto, true) ?? [];
    
        if ($request->hasFile('bukti_foto')) {
            foreach ($request->file('bukti_foto') as $file) {
                $fileName = $file->store('bukti_foto', 'public');
                $fileNames[] = basename($fileName);
            }
        }
    
        if ($request->has('existing_images')) {
            $existingImages = $request->input('existing_images');
            $imagesToDelete = array_diff($fileNames, $existingImages);
    
            foreach ($imagesToDelete as $image) {
                $filePath = storage_path('app/public/bukti_foto/' . $image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
                $fileNames = $existingImages;
        }
    
        $perkara->update([
            'title' => $request->title,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'deskripsi_kejadian' => $request->deskripsi_kejadian,
            'bukti_foto' => json_encode($fileNames),
        ]);
    
        return response()->json([
            'success' => true,
            'redirect_url' => route('perkara.index')
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perkaras = Perkara::where('id', $id)
        ->select('id', 'title', 'tanggal_kejadian', 'status', 'deskripsi_kejadian', 'bukti_foto', 'id_organization',)
        ->with([
            'organisasi' => function ($query) {
                $query->select(
                    'id',
                    'organisasi_nama',
                    'organisasi_status',
                    'organisasi_type_id',
                );
            }
        ])->first();
    return view('Perkara.All.Show', ['perkaras' => $perkaras]);
    }

    public function showProdi(string $id)
    {
        $perkaras = Perkara::select(
                'perkaras.id',
                'perkaras.title',
                'perkaras.tanggal_kejadian',
                'perkaras.status',
                'perkaras.deskripsi_kejadian',
                'perkaras.bukti_foto',
                'perkaras.id_organization',
                'perkaras.id_prodi',
                'organisasis.organisasi_nama',
                'organisasis.organisasi_status',
                'organisasis.organisasi_type_id',
                'program_studis.prodi_nama'
            )
            ->leftJoin('program_studis', 'perkaras.id_prodi', '=', 'program_studis.id')
            ->leftJoin('organisasis', 'program_studis.id_organization', '=', 'organisasis.id')
            ->where('perkaras.id', $id)
            ->first();
    
        return view('Perkara.All.ShowProdi', ['perkaras' => $perkaras]);
    }
}
