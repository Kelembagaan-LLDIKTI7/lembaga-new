<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = DB::table('permissions')->get();

        return view('Permission.Index', ['permission' => $permission]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'string|required|unique:permissions,name',
        ]);
        $validateData['guard_name'] = $validateData['guard_name'] ?? 'web';

        $permission = Permission::create($validateData);

        if ($permission) {
            return to_route('permission.index')->with('success', 'Data Telah Ditambahkan');
        } else {
            return to_route('permission.index')->with('failed', 'Data Gagal Ditambahkan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validateData = $request->validate([
            'name' => 'string|required|unique:permissions,name,' . $permission->id,
        ]);

        $validateData['guard_name'] = $validateData['guard_name'] ?? 'web';
        $permission->update($validateData);

        if ($permission) {
            return to_route('permission.index')->with('success', 'Data Berhasil Diubah');
        } else {
            return to_route('permission.index')->with('failed', 'Data Gagal Diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        if ($permission) {
            return to_route('permission.index')->with('success', 'Data Telah Dihapus');
        } else {
            return to_route('permission.index')->with('failed', 'Data Gagal Dihapus');
        }
    }
}
