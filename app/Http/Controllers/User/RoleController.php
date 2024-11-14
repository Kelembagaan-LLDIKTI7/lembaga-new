<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('Roles.Index', compact('roles'));
    }

    private function validateRoleData(Request $request, $roleId = null)
    {
        $rules = [
            'name' => 'required|string|unique:roles,name' . ($roleId ? ',' . $roleId : ''),
        ];

        $messages = [
            'name.required' => 'Nama Role harus diisi.',
            'name.string' => 'Nama Role harus berupa string.',
            'name.unique' => 'Nama Role sudah digunakan.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateRoleData($request);
        $validatedData['guard_name'] = 'web';

        $role = Role::create($validatedData);

        if ($role) {
            session()->flash('success', 'Data Telah Ditambahkan');
        } else {
            session()->flash('failed', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('roles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $this->validateRoleData($request, $role->id);
        $validatedData['guard_name'] = 'web';

        $updated = $role->update($validatedData);

        if ($updated) {
            session()->flash('success', 'Data Berhasil Diubah');
        } else {
            session()->flash('failed', 'Data Gagal Diubah');
        }

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $deleted = $role->delete();

        return $deleted ? redirect()->route('roles.index')->with('success', 'Data Telah Dihapus')
            : redirect()->route('roles.index')->with('failed', 'Data Gagal Dihapus');
    }

    public function addPermissionToRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->toArray();

        return view('Roles.addRolePermission', compact('role', 'permissions', 'rolePermissions'));
    }

    public function storePermissionToRole(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|array',
        ]);

        $role->syncPermissions($request->permission);

        return redirect()->route('roles.index')->with('success', 'Permissions successfully updated.');
    }
}
