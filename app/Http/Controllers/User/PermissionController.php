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

    private function validatePermissionData(Request $request, $permissionId = null)
    {
        $rules = [
            'name' => 'required|string|unique:permissions,name' . ($permissionId ? ',' . $permissionId : ''),
        ];

        $messages = [
            'name.required' => 'Nama Permission harus diisi.',
            'name.string' => 'Nama Permission harus berupa string.',
            'name.unique' => 'Nama Permission sudah digunakan.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validatePermissionData($request);
        $validatedData['guard_name'] = 'web';

        $permission = Permission::create($validatedData);

        if ($permission) {
            session()->flash('success', 'Data Telah Ditambahkan');
        } else {
            session()->flash('failed', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('permission.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validatedData = $this->validatePermissionData($request, $permission->id);
        $validatedData['guard_name'] = 'web';

        $updated = $permission->update($validatedData);

        if ($updated) {
            session()->flash('success', 'Data Telah Ditambahkan');
        } else {
            session()->flash('failed', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('permission.index');
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
