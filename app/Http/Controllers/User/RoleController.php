<?php

namespace App\Http\Controllers;

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
        return view('role.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'string|required|unique:roles,name',
        ]);
        $validateData['guard_name'] = 'web';

        $role = Role::create($validateData);

        return $role ? redirect()->route('roles.index')->with('success', 'Data Telah Ditambahkan')
            : redirect()->route('roles.index')->with('failed', 'Data Gagal Ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validateData = $request->validate([
            'name' => 'string|required|unique:roles,name,' . $role->id,
        ]);

        $validateData['guard_name'] = 'web';
        $updated = $role->update($validateData);

        return $updated ? redirect()->route('roles.index')->with('success', 'Data Berhasil Diubah')
            : redirect()->route('roles.index')->with('failed', 'Data Gagal Diubah');
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

        return view('role.addRolePermission', compact('role', 'permissions', 'rolePermissions'));
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
