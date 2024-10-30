<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Akta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SkKumhamController extends Controller
{
    public function createSession(Request $request)
    {
        session(['akta_uuid' => $request->id]);

        $akta = DB::table('aktas')
            ->where('aktas.id', $request->id)
            ->leftJoin('sk_kumhams', 'aktas.id', '=', 'sk_kumhams.id_akta')
            ->select('aktas.*', 'sk_kumhams.id as kumham_id', 'sk_kumhams.kumham_nomor', 'sk_kumhams.kumham_perihal', 'sk_kumhams.kumham_tanggal', 'sk_kumhams.kumham_dokumen')
            ->first();

        session(['org_akta' => $akta->id_organization]);

        if ($akta->kumham_id) {
            return redirect()->route('sk-kumham.edit', ['id' => $akta->kumham_id]);
        }

        return redirect()->route('sk-kumham.create');
    }
    public function create()
    {
        $akta_uuid = session('akta_uuid');

        $akta = DB::table('aktas')
            ->where('id', $akta_uuid)
            ->first();

        return view('sk-kumham.create', compact('akta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kumham_nomor' => 'required|string|unique:sk_kumhams',
            'kumham_perihal' => 'required',
            'kumham_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'kumham_dokumen' => 'required|mimes:pdf|max:10240',
            'id_akta' => 'required|uuid|exists:aktas,id',
        ], [
            'kumham_nomor.required' => 'Nomor SK Kumham wajib diisi',
            'kumham_nomor.string' => 'Nomor SK Kumham harus berupa string',
            'kumham_nomor.unique' => 'Nomor SK Kumham sudah terdaftar',
            'kumham_perihal.required' => 'Perihal SK Kumham wajib diisi',
            'kumham_tanggal.required' => 'Tanggal SK Kumham wajib diisi',
            'kumham_dokumen.required' => 'Dokumen SK Kumham wajib diisi',
            'kumham_dokumen.mimes' => 'Dokumen SK Kumham harus berupa file PDF',
            'kumham_dokumen.max' => 'Dokumen SK Kumham maksimal 10MB',
            'id_akta.required' => 'Akta wajib diisi',
            'id_akta.uuid' => 'Akta harus berupa UUID',
            'id_akta.exists' => 'Akta tidak ditemukan',
        ]);

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->kumham_tanggal)->format('Y-m-d');

        DB::beginTransaction();
        try {
            $id_kumham = Str::uuid()->toString();
            $filename_kumham = $this->generateFileName($request->file('kumham_dokumen')->getClientOriginalExtension(), 'kumham_dokumen', $id_kumham);

            $request->file('kumham_dokumen')->storeAs('dokumen/kumham/', $filename_kumham, 'public');

            DB::table('sk_kumhams')->insert([
                'id' => $id_kumham,
                'kumham_nomor' => $request->kumham_nomor,
                'kumham_perihal' => $request->kumham_perihal,
                'kumham_tanggal' => $formatted_date,
                'kumham_dokumen' => $filename_kumham,
                'id_akta' => $request->id_akta,
                'created_at' => now(),
            ]);

            Log::info('SK Kumham created ' . $id_kumham);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        $akta = Akta::find($request->id_akta);

        session(['org_uuid' => $akta->id_organization]);

        return redirect()->route('akta.index');
    }

    public function edit(string $id)
    {
        $sk_kumham = DB::table('sk_kumhams')
            ->where('id', $id)
            ->first();

        // dd($sk_kumham);

        return view('sk-kumham.edit', compact('sk_kumham'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kumham_nomor' => 'required|string',
            'kumham_perihal' => 'required',
            'kumham_tanggal' => ['required', function ($attribute, $value, $fail) {
                $date = Carbon::createFromFormat('d M, Y', $value);
                if (!$date) {
                    $fail('Format tanggal tidak valid.');
                }
            }],
            'kumham_dokumen' => 'nullable|mimes:pdf|max:10240',
            'id_akta' => 'required|uuid|exists:aktas,id',
        ], [
            'kumham_nomor.required' => 'Nomor SK Kumham wajib diisi',
            'kumham_nomor.string' => 'Nomor SK Kumham harus berupa string',
            'kumham_nomor.unique' => 'Nomor SK Kumham sudah terdaftar',
            'kumham_perihal.required' => 'Perihal SK Kumham wajib diisi',
            'kumham_tanggal.required' => 'Tanggal SK Kumham wajib diisi',
            'kumham_tanggal.date' => 'Tanggal SK Kumham harus berupa tanggal',
            'kumham_dokumen.mimes' => 'Dokumen SK Kumham harus berupa file PDF',
            'kumham_dokumen.max' => 'Dokumen SK Kumham maksimal 10MB',
            'id_akta.required' => 'Akta wajib diisi',
            'id_akta.uuid' => 'Akta harus berupa UUID',
            'id_akta.exists' => 'Akta tidak ditemukan',
        ]);

        $formatted_date = Carbon::createFromFormat('d M, Y', $request->kumham_tanggal)->format('Y-m-d');

        DB::beginTransaction();
        try {
            if ($request->hasFile('kumham_dokumen')) {
                $sk_kumham = DB::table('sk_kumhams')
                    ->where('id', $request->id)
                    ->first();

                $filename_kumham = $this->generateFileName($request->file('kumham_dokumen')->getClientOriginalExtension(), 'kumham_dokumen', $request->id);

                $request->file('kumham_dokumen')->storeAs('dokumen/kumham/', $filename_kumham, 'public');

                DB::table('sk_kumhams')
                    ->where('id', $request->id)
                    ->update([
                        'kumham_nomor' => $request->kumham_nomor,
                        'kumham_perihal' => $request->kumham_perihal,
                        'kumham_tanggal' => $formatted_date,
                        'kumham_dokumen' => $filename_kumham,
                        'id_akta' => $request->id_akta,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('sk_kumhams')
                    ->where('id', $request->id)
                    ->update([
                        'kumham_nomor' => $request->kumham_nomor,
                        'kumham_perihal' => $request->kumham_perihal,
                        'kumham_tanggal' => $formatted_date,
                        'id_akta' => $request->id_akta,
                        'updated_at' => now(),
                    ]);
            }

            Log::info('SK Kumham updated ' . $request->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('akta.index');
    }

    public function createPdfSession(Request $request)
    {
        session(['pdf_kumham' => $request->kumham_dokumen]);

        return redirect()->route('sk-kumham.viewPdf');
    }

    public function viewPdf()
    {
        $filename = session('pdf_kumham');
        $path = storage_path('app/public/dokumen/kumham/' . $filename);

        return response()->file($path);
    }
}
