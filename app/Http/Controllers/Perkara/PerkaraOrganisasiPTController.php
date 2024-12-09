<?php

namespace App\Http\Controllers\Perkara;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\Perkara;
use Illuminate\Http\Request;

class PerkaraOrganisasiPTController extends Controller
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
        $organisasi = Organisasi::select('id')->findOrFail($id);
        return view('Perkara.OrganisasiPt.Create', ['organisasi' => $organisasi]);
    }

    public function validationStore(Request $request)
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
            'no_perkara.max' => 'Nomor Perkara maksimal 255 karakter',
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'no_perkara' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'deskripsi_kejadian' => 'required|string',
            'bukti_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Judul perkara harus diisi.',
            'title.max' => 'Judul perkara maksimal 255 karakter.',
            'no_perkara.max' => 'Nomor perkara maksimal 255 karakter',
            'tanggal_kejadian.required' => 'Tanggal kejadian harus diisi.',
            'tanggal_kejadian.date' => 'Tanggal kejadian harus berupa tanggal.',
            'deskripsi_kejadian.required' => 'Deskripsi kejadian harus diisi.',
            'bukti_foto.image' => 'Bukti foto harus berupa gambar.',
            'bukti_foto.mimes' => 'Bukti foto harus berformat JPEG, PNG, JPG, atau GIF.',
            'bukti_foto.max' => 'Bukti foto maksimal 2048 KB.',
        ]);

        $fileNames = [];

        if ($request->hasFile('bukti_foto')) {
            foreach ($request->file('bukti_foto') as $file) {
                $fileName = $file->store('bukti_foto', 'public');
                $fileNames[] = basename($fileName);
            }

            Perkara::create([
                'title' => $request->title,
                'no_perkara' => $request->no_perkara,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'deskripsi_kejadian' => $request->deskripsi_kejadian,
                'bukti_foto' => json_encode($fileNames),
                'id_organization' => $request->id_organization,
                'status' => 'Berjalan'
            ]);
        } else {
            Perkara::create([
                'title' => $request->title,
                'no_perkara' => $request->no_perkara,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'deskripsi_kejadian' => $request->deskripsi_kejadian,
                'id_organization' => $request->id_organization,
                'status' => 'Berjalan'
            ]);
        }

        session()->flash('success', 'Perkara berhasil ditambahkan');

        return response()->json([
            'success' => true,
            'redirect_url' => route('perguruan-tinggi.show', ['id' => $request->id_organization])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perkaras = Perkara::where('id', $id)
            ->select('id', 'title', 'no_perkara', 'tanggal_kejadian', 'status', 'deskripsi_kejadian', 'bukti_foto', 'id_organization')
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
        return view('Perkara.OrganisasiPt.Show', ['perkaras' => $perkaras]);
        // return response()->json(['perkaras' => $perkaras]);
    }

    public function validationUpdate(Request $request, string $id)
    {
        $validator = \Validator::make($request->all(), [
            'status' => 'required|string',
        ], [
            'status.required' => 'Status perkara harus diisi.',
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ], [
            'status.required' => 'Status perkara harus diisi.',
        ]);

        $perkara = Perkara::findOrFail($id);

        $perkara->status = $request->input('status');
        $perkara->save();

        return redirect()->back()->with('success', 'Status perkara berhasil diperbarui.');
    }

    /**
     * Validate the request data for editing Perkara.
     */
    public function validationEdit(Request $request, string $id)
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
            'no_perkara.max' => 'Nomor Perkara Maksimal 255 karakter',
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

        return view('Perkara.OrganisasiPt.Edit', [
            'perkaras' => $perkaras,
            'organisasi' => $organisasi,
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

        return response()->json([
            'success' => true,
            'redirect_url' => route('evaluasi-organisasipt.show', ['id' => $id]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
