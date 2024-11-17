<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $user = User::all();
            $organization = Organisasi::all();
        } else {
            $userOrganizationId = Auth::user()->id_organization;
            $organizationIds = Organisasi::where('id', $userOrganizationId)
                ->orWhere('parent_id', $userOrganizationId)
                ->with('children')
                ->get()
                ->pluck('id')
                ->toArray();
            $user = User::whereIn('id_organization', $organizationIds)->get();

            $organization = Organisasi::whereIn('id', $organizationIds)->get();
        }

        return view('User.Index', ['user' => $user, 'organization' => $organization]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        if (Auth::user()->hasRole('Super Admin')) {
            $organization = Organisasi::withDescendants()->get();
        } else {
            $userOrganizationId = Auth::user()->id_organization;
            $organization = Organisasi::where('id', $userOrganizationId)
                ->orWhere('parent_id', $userOrganizationId)
                ->withDescendants()
                ->get();
        }

        return view('User.Create', [
            'roles' => $roles,
            'organization' => $organization,
        ]);
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
            'id_organization' => 'string|required|exists:organisasis,id',
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
        return view('User.Detail', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }
    
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();

        if (Auth::user()->hasRole('Super Admin')) {
            $organization = Organisasi::withDescendants()->get();
        } else {
            $userOrganizationId = Auth::user()->id_organization;
            $organization = Organisasi::where('id', $userOrganizationId)
                ->orWhere('parent_id', $userOrganizationId)
                ->withDescendants()
                ->get();
        }

        return view('User.Edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'organization' => $organization,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }
            
        $validateData = $request->validate([
            'name' => 'string|required',
            'email' => 'string|required|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:10',
            'nip' => 'required|string|max:50|unique:users,nip,' . $user->id,
            'is_active' => 'boolean|required',
            'id_organization' => 'string|required|exists:organisasis,id',
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

    public function updatePassword(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:10',
                'confirmed'
            ]
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.string' => 'Password baru harus berupa teks.',
            'new_password.min' => 'Password baru minimal harus terdiri dari 10 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }
}
