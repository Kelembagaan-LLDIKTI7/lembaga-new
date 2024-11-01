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

            'Create Perguruan Tinggi',
            'Edit Perguruan Tinggi',
            'Delete Perguruan Tinggi',
            'View Perguruan Tinggi',
            'Import Perguruan Tinggi',
            'Detail Perguruan Tinggi',

            'Create Badan Penyelenggara',
            'Edit Badan Penyelenggara',
            'Delete Badan Penyelenggara',
            'View Badan Penyelenggara',
            'Import Badan Penyelenggara',
            'Detail Badan Penyelenggara',

            'Create Program Studi',
            'Edit Program Studi',
            'View Program Studi',
            'Detail Program Studi',

            'Create Akreditasi Program Studi',
            'Edit Akreditasi Program Studi',
            'View Akreditasi Program Studi',
            'Detail Akreditasi Program Studi',

            'Create Akreditasi Perguruan Tinggi',
            'Edit Akreditasi Perguruan Tinggi',
            'Delete Akreditasi Perguruan Tinggi',
            'View Akreditasi Perguruan Tinggi',
            'Detail Akreditasi Perguruan Tinggi',
            'View PDF Akreditasi Perguruan Tinggi',

            'Create Pimpinan Perguruan Tinggi',
            'Edit Pimpinan Perguruan Tinggi',
            'Delete Pimpinan Perguruan Tinggi',
            'View Pimpinan Perguruan Tinggi',
            'Detail Pimpinan Perguruan Tinggi',
            'View PDF Pimpinan Perguruan Tinggi',

            'Create SK Perguruan Tinggi',
            'Edit SK Perguruan Tinggi',
            'Delete SK Perguruan Tinggi',
            'View SK Perguruan Tinggi',
            'Detail SK Perguruan Tinggi',
            'View PDF SK Perguruan Tinggi',

            'Create Pimpinan Badan Penyelenggara',
            'Edit Pimpinan Badan Penyelenggara',
            'Delete Pimpinan Badan Penyelenggara',
            'View Pimpinan Badan Penyelenggara',
            'Detail Pimpinan Badan Penyelenggara',
            'View PDF Pimpinan Badan Penyelenggara',

            'Create Akta Badan Penyelenggara',
            'Edit Akta Badan Penyelenggara',
            'Delete Akta Badan Penyelenggara',
            'View Akta Badan Penyelenggara',
            'Detail Akta Badan Penyelenggara',
            'View PDF Akta Badan Penyelenggara',

            'Create SK Kumham Badan Penyelenggara',
            'Edit SK Kumham Badan Penyelenggara',
            'Delete SK Kumham Badan Penyelenggara',
            'View SK Kumham Badan Penyelenggara',
            'Detail SK Kumham Badan Penyelenggara',
            'View PDF SK Kumham Badan Penyelenggara',
            
            'Create Jabatan',
            'Edit Jabatan',
            'Delete Jabatan',
            'View Jabatan',
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
