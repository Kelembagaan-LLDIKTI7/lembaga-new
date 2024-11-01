<?php

namespace Database\Seeders;

use App\Models\Organisasi;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected static ?string $password;

    public function run(): void
    {
        $role = Role::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $role = Role::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Guest',
            'guard_name' => 'web',
        ]);

        $organization = Organisasi::where('organisasi_nama', 'Lembaga Layanan Pendidikan Tinggi Wilayah VII')->first();

        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'super_admin',
            'nip' => '200301102025011001',
            'email' => 'wsobirin2@gmail.com',
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => 1,
            'id_organization' => $organization->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->assignRole($role);

        $permissions = [
            'Create Permission',
            'Edit Permission',
            'Delete Permission',
            'View Permission',

            'Create Roles',
            'Edit Roles',
            'Delete Roles',
            'View Roles',

            'View Role Permissions',
            'Add Role Permissions', 

            'Create User',
            'Edit User',
            'Delete User',
            'View User',
            
            'Create Akreditasi',
            'Edit Akreditasi',
            'Delete Akreditasi',
            'View Akreditasi',
            'Create Akta',
            'Edit Akta',
            'Delete Akta',
            'View Akta',
            'Create Program Studi',
            'Edit Program Studi',
            'Delete Program Studi',
            'View Program Studi',
            'Create Jabatan',
            'Edit Jabatan',
            'Delete Jabatan',
            'View Jabatan',
            'Create Surat Keputusan',
            'Edit Surat Keputusan',
            'Delete Surat Keputusan',
            'View Surat Keputusan',
            'Create Perguruan Tinggi',
            'Edit Perguruan Tinggi',
            'Delete Perguruan Tinggi',
            'View Perguruan Tinggi',
            'Create Badan Penyelenggara',
            'Edit Badan Penyelenggara',
            'Delete Badan Penyelenggara',
            'View Badan Penyelenggara',
            'Create SK Kumham',
            'Edit SK Kumham',
            'Delete SK Kumham',
            'View SK Kumham',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create([
                'id' => Str::uuid()->toString(),
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $role->givePermissionTo($permission);
        }
    }
}
