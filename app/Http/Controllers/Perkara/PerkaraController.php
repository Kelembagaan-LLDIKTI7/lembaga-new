<?php

namespace App\Http\Controllers\Perkara;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Organisasi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perkarasOrg = Perkara::with(['organisasi:id,organisasi_nama'])->whereNotNull('id_organization')->get();
        $perkarasProdi = Perkara::select(
            'perkaras.*',
            'organisasis.organisasi_nama',
            'program_studis.prodi_nama'
        )
            ->join('program_studis', DB::raw('perkaras.id_prodi COLLATE utf8mb4_unicode_ci'), '=', DB::raw('program_studis.id COLLATE utf8mb4_unicode_ci'))
            ->join('organisasis', DB::raw('program_studis.id_organization COLLATE utf8mb4_unicode_ci'), '=', DB::raw('organisasis.id COLLATE utf8mb4_unicode_ci'))
            ->whereNotNull('perkaras.id_prodi')->get();


        return view('Perkara.All.Index', ['perkarasOrg' => $perkarasOrg, 'perkarasProdi' => $perkarasProdi]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function validationUpdate(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'no_perkara' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Judul perkara harus diisi.',
            'title.max' => 'Judul perkara maksimal 255 karakter.',
            'no_perkara.max' => 'Nomor perkara maksimal 255 karakter.',
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
        $perkaras = Perkara::findOrFail($id);
        $existingImages = json_decode($perkaras->bukti_foto, true) ?? [];

        $organisasi = Organisasi::select('id', 'organisasi_nama')->get();
        $prodi = ProgramStudi::select('id', 'prodi_nama')->get();

        return view('Perkara.All.Edit', [
            'perkaras' => $perkaras,
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
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'no_perkara' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $perkaras = Perkara::findOrFail($id);

        $currentImages = json_decode($perkaras->bukti_foto, true) ?? [];

        $existingImages = $request->input('existing_images', []);
        $fileNames = array_values($existingImages); 

        if ($request->hasFile('bukti_foto')) {
            foreach ($request->file('bukti_foto') as $file) {
                $fileName = $file->store('bukti_foto', 'public');
                $fileNames[] = basename($fileName);
            }
        }

        $imagesToDelete = array_diff($currentImages, $existingImages);

        foreach ($imagesToDelete as $image) {
            $filePath = storage_path('app/public/bukti_foto/' . $image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $perkaras->update([
            'title' => $request->title,
            'no_perkara' => $request->no_perkara,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'deskripsi_kejadian' => $request->deskripsi_kejadian,
            'bukti_foto' => json_encode($fileNames),
        ]);

        $redirectRoute = $request->input('redirect_route', route('perkara.show', ['id' => $id]));

        return response()->json([
            'success' => true,
            'redirect_url' => $redirectRoute,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perkaras = Perkara::where('id', $id)
            ->select('id', 'title', 'no_perkara', 'tanggal_kejadian', 'status', 'deskripsi_kejadian', 'bukti_foto', 'id_organization',)
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

    public function editprodi(string $id)
    {
        $perkaras = Perkara::findOrFail($id);
        $existingImages = json_decode($perkaras->bukti_foto, true) ?? [];

        $organisasi = Organisasi::select('id', 'organisasi_nama')->get();
        $prodi = ProgramStudi::select('id', 'prodi_nama')->get();

        return view('Perkara.All.EditProdi', [
            'perkaras' => $perkaras,
            'organisasi' => $organisasi,
            'prodi' => $prodi,
            'existingImages' => $existingImages,
        ]);
    }

    public function updateprodi(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'no_perkara' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        $perkaras = Perkara::findOrFail($id);

        $currentImages = json_decode($perkaras->bukti_foto, true) ?? [];

        $existingImages = $request->input('existing_images', []);
        $fileNames = array_values($existingImages); 

        if ($request->hasFile('bukti_foto')) {
            foreach ($request->file('bukti_foto') as $file) {
                $fileName = $file->store('bukti_foto', 'public');
                $fileNames[] = basename($fileName);
            }
        }

        $imagesToDelete = array_diff($currentImages, $existingImages);

        foreach ($imagesToDelete as $image) {
            $filePath = storage_path('app/public/bukti_foto/' . $image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $perkaras->update([
            'title' => $request->title,
            'no_perkara' => $request->no_perkara,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'deskripsi_kejadian' => $request->deskripsi_kejadian,
            'bukti_foto' => json_encode($fileNames),
        ]);

        $redirectRoute = $request->input('redirect_route', route('perkara.showprodi', ['id' => $id]));

        return response()->json([
            'success' => true,
            'redirect_url' => $redirectRoute,
        ]);
    }


    public function showprodi(string $id)
    {
        $perkaras = Perkara::select(
            'perkaras.id',
            'perkaras.title',
            'perkaras.no_perkara',
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
