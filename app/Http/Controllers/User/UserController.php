<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        return view('user.view', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $organizations = Organisasi::all();

        return view('user.create', ['roles' => $roles, 'organizations' => $organizations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'string|required',
            'email' => 'string|required|unique:users',
            'password' => 'string|required|min:10',
            'nip' => 'string|required',
            'is_active' => 'boolean',
            'id_organization' => 'string|required|exists:organizations,id',
            'roles' => 'required',
        ]);

        $validateData['password'] = Hash::make($validateData['password']);

        $user = User::create($validateData);

        $user->assignRole($request->input('roles'));

        return $user
            ? to_route('user.index')->with('success', 'User successfully added')
            : to_route('user.index')->with('failed', 'Failed to add user');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.detail', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        $organizations = Organisasi::all();

        return view('user.edit', ['user' => $user, 'roles' => $roles, 'userRoles' => $userRoles, 'organizations' => $organizations]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validateData = $request->validate([
            'name' => 'string|required',
            'email' => 'string|required|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:10',
            'nip' => 'required|string|max:50|unique:users,nip,' . $user->id,
            'is_active' => 'boolean|required',
            'id_organization' => 'string|required|exists:organizations,id',
            'roles' => 'required',
        ]);

        // Only hash the password if it was provided
        if ($request->filled('password')) {
            $validateData['password'] = Hash::make($validateData['password']);
        } else {
            unset($validateData['password']);
        }

        $user->update($validateData);

        // Update roles
        $user->syncRoles($request->input('roles'));

        return $user
            ? to_route('user.index')->with('success', 'User successfully updated')
            : to_route('user.index')->with('failed', 'Failed to update user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $user
            ? to_route('user.index')->with('success', 'User successfully deleted')
            : to_route('user.index')->with('failed', 'Failed to delete user');
    }
}
