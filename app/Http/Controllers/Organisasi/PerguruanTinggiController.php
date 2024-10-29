<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PerguruanTinggiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_nama as pt_nama',
                'organisasi_nama_singkat',
                'organisasi_email',
                'organisasi_telp',
                'organisasi_kota',
                'organisasi_status',
                'parent_id'
            )
            ->with('parent:id,organisasi_nama')
            ->orderBy('pt_nama', 'asc')
            ->get();

        return view('Organisasi.PerguruanTinggi.Index', [
            'perguruanTinggis' => $perguruanTinggis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $badanPenyelenggaras = Organisasi::where('organisasi_type_id', 2)
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        $perguruanTinggis = Organisasi::where('organisasi_type_id', 3)
            ->select(
                'id',
                'organisasi_nama'
            )->get();

        return view('Organisasi.PerguruanTinggi.Create', [
            'badanPenyelenggaras' => $badanPenyelenggaras,
            'perguruanTinggis' => $perguruanTinggis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organisasi_nama' => 'required|string|max:255',
            'organisasi_nama_singkat' => 'nullable|string|max:255',
            'organisasi_email' => 'required|email|max:255',
            'organisasi_telp' => 'required|string|max:15',
            'organisasi_kota' => 'required|string|max:100',
            'organisasi_alamat' => 'required|string|max:255',
            'organisasi_website' => 'nullable|url|max:255',
            'organisasi_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable',
        ]);

        if ($request->input('changeType') === 'penyatuan' || $request->input('changeType') === 'penggabungan') {
            $request->validate([
                'perguruan_tinggi_1' => 'required',
                'perguruan_tinggi_2' => 'required',
                'perguruan_tinggi_tambahan.*' => 'nullable'
            ]);
        }

        $organisasiBerubahId = [];

        if ($request->has('perguruan_tinggi_1') && $request->has('perguruan_tinggi_2')) {
            $organisasiBerubahId[] = $request->input('perguruan_tinggi_1');
            $organisasiBerubahId[] = $request->input('perguruan_tinggi_2');
        }

        if ($request->has('perguruan_tinggi_tambahan')) {
            foreach ($request->input('perguruan_tinggi_tambahan') as $ptTambahan) {
                if (!is_null($ptTambahan)) {
                    $organisasiBerubahId[] = $ptTambahan;
                }
            }
        }

        if (!empty($organisasiBerubahId)) {
            Organisasi::whereIn('id', $organisasiBerubahId)->update(['organisasi_status' => 'Tidak Aktif']);
        }

        $organisasiStatusBaru = 'Aktif';

        if ($request->input('changeType') === 'penyatuan') {
            $organisasiStatusBaru = 'Penyatuan';
        } elseif ($request->input('changeType') === 'penggabungan') {
            $organisasiStatusBaru = 'Penggabungan';
        }

        if ($request->hasFile('organisasi_logo')) {
            $logoPath = $request->file('organisasi_logo')->store('logos', 'public');
        }

        Organisasi::create([
            'id' => Str::uuid(),
            'organisasi_nama' => $validated['organisasi_nama'],
            'organisasi_email' => $validated['organisasi_email'],
            'organisasi_telp' => $validated['organisasi_telp'],
            'organisasi_kota' => $validated['organisasi_kota'],
            'organisasi_nama_singkat' => $validated['organisasi_nama_singkat'],
            'organisasi_website' => $validated['organisasi_website'] ?? null,
            'organisasi_alamat' => $validated['organisasi_alamat'],
            'organisasi_logo' => $logoPath ?? null,
            'organisasi_type_id' => 3,
            'organisasi_status' => $organisasiStatusBaru,
            'organisasi_berubah_id' => !empty($organisasiBerubahId) ? json_encode($organisasiBerubahId) : null,
            'parent_id' => $validated['parent_id'],
        ]);

        return redirect()->route('perguruan-tinggi.index')->with('success', 'Perguruan Tinggi berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organisasi = Organisasi::findOrFail($id);

        $berubahIds = json_decode($organisasi->organisasi_berubah_id, true);

        if (!empty($berubahIds)) {
            $berubahOrganisasi = Organisasi::whereIn('id', $berubahIds)
                ->select(
                    'id',
                    'organisasi_nama',
                    'organisasi_nama_singkat',
                    'organisasi_email',
                    'organisasi_telp',
                    'organisasi_kota',
                    'organisasi_status',
                    'organisasi_alamat',
                )
                ->orderBy('organisasi_nama', 'asc')
                ->get();
        } else {
            $berubahOrganisasi = collect();
        }

        return view('Organisasi.PerguruanTinggi.Show', [
            'organisasi' => $organisasi,
            'berubahOrganisasi' => $berubahOrganisasi
        ]);
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
