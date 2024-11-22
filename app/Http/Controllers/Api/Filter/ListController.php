<?php

namespace App\Http\Controllers\Api\Filter;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function getList(Request $request)
    {
        $data = [];

        $pt = DB::table('organisasis')
            ->select('id', 'organisasi_nama')
            ->where('organisasi_type_id', 3)
            ->orderBy('organisasi_nama', 'asc')
            ->get();

        $data['perguruan_tinggi'] = $pt;

        $program = [
            'D1' => 'D1',
            'D2' => 'D2',
            'D3' => 'D3',
            'D4' => 'D4',
            'Profesi' => 'Profesi',
            'S1' => 'S1',
            'S2' => 'S2',
            'S3' => 'S3',
        ];

        $data['program'] = $program;

        $bentuk = DB::table('bentuk_pts')
            ->select('id', 'bentuk_nama')
            ->orderBy('bentuk_nama', 'asc')
            ->get();

        $data['bentuk_pt'] = $bentuk;

        $kota = DB::table('kotas')
            ->select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->get();

        $data['kota'] = $kota;

        return response()->json($data);
    }
}
