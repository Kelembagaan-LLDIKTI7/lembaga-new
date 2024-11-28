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
    protected static ?string $password;

    public function run(): void
    {
        $superAdminRole = Role::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

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
            'View History Perguruan Tinggi',
            'Export Perguruan Tinggi',

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
            'Export Program Studi',

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

            'Create SK Program Studi',
            'Edit SK Program Studi',
            'Delete SK Program Studi',
            'View SK Program Studi',
            'Detail SK Program Studi',
            'View PDF SK Program Studi',

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

            'View SK Badan Penyelenggara',
            'Create SK Badan Penyelenggara',
            'Edit SK Badan Penyelenggara',
            'View PDF SK Badan Penyelenggara',

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

            'View Peringkat Akreditasi',
            'Create Peringkat Akreditasi',
            'Edit Peringkat Akreditasi',
            'Delete Peringkat Akreditasi',

            'View Lembaga Akreditasi',
            'Create Lembaga Akreditasi',
            'Edit Lembaga Akreditasi',
            'Delete Lembaga Akreditasi',

            'View Jenis Organisasi',
            'Create Jenis Organisasi',
            'Edit Jenis Organisasi',
            'Delete Jenis Organisasi',

            'View Evaluasi Master',
            'Edit Evaluasi Master',
            'View Detail Evaluasi Master',

            'Create Evaluasi Badan Penyelenggara',
            'Edit Evaluasi Badan Penyelenggara',
            'View Detail Evaluasi Badan Penyelenggara',
            'Update Status Evaluasi Badan Penyelenggara',

            'Create Evaluasi Perguruan Tinggi',
            'Edit Evaluasi Perguruan Tinggi',
            'View Detail Evaluasi Perguruan Tinggi',
            'Update Status Evaluasi Perguruan Tinggi',

            'Create Evaluasi Program Studi',
            'Edit Evaluasi Program Studi',
            'View Detail Evaluasi Program Studi',
            'Update Status Evaluasi Program Studi',

            'View Detail Evaluasi (Dashboard)',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create([
                'id' => Str::uuid()->toString(),
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $superAdminRole->givePermissionTo($permission);
        }

        $perguruanTinggiRole = Role::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Perguruan Tinggi',
            'guard_name' => 'web',
        ]);

        $perguruanTinggiPermissions = [
            'Create Perguruan Tinggi',
            'Edit Perguruan Tinggi',
            'Delete Perguruan Tinggi',
            'View Perguruan Tinggi',
            'Import Perguruan Tinggi',
            'Detail Perguruan Tinggi',
            'View History Perguruan Tinggi',
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
            'Create SK Program Studi',
            'Edit SK Program Studi',
            'Delete SK Program Studi',
            'View SK Program Studi',
            'Detail SK Program Studi',
            'View PDF SK Program Studi',
        ];

        foreach ($perguruanTinggiPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $perguruanTinggiRole->givePermissionTo($permission);
        }

        $badanPenyelenggaraRole = Role::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Badan Penyelenggara',
            'guard_name' => 'web',
        ]);

        $badanPenyelenggaraPermissions = array_merge($perguruanTinggiPermissions, [
            'Create Badan Penyelenggara',
            'Edit Badan Penyelenggara',
            'Delete Badan Penyelenggara',
            'View Badan Penyelenggara',
            'Import Badan Penyelenggara',
            'Detail Badan Penyelenggara',
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
            'View SK Badan Penyelenggara',
            'Create SK Badan Penyelenggara',
            'Edit SK Badan Penyelenggara',
            'View PDF SK Badan Penyelenggara',
            'Create SK Kumham Badan Penyelenggara',
            'Edit SK Kumham Badan Penyelenggara',
            'Delete SK Kumham Badan Penyelenggara',
            'View SK Kumham Badan Penyelenggara',
            'Detail SK Kumham Badan Penyelenggara',
            'View PDF SK Kumham Badan Penyelenggara',
            'Create Perguruan Tinggi',
            'Edit Perguruan Tinggi',
            'Delete Perguruan Tinggi',
            'View Perguruan Tinggi',
            'Import Perguruan Tinggi',
            'Detail Perguruan Tinggi',
            'View History Perguruan Tinggi',
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
        ]);

        foreach ($badanPenyelenggaraPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $badanPenyelenggaraRole->givePermissionTo($permission);
        }

        $organization = Organisasi::where('organisasi_nama', 'Lembaga Layanan Pendidikan Tinggi Wilayah VII')->first();

        $superAdmin = User::create([
            'id' => Str::uuid(),
            'name' => 'super_admin',
            'nip' => '200301102025011001',
            'email' => 'wsobirin2@gmail.com',
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => 1,
            'id_organization' => $organization->id,
        ]);
        $superAdmin->assignRole($superAdminRole);

        $acxell = User::create([
            'id' => Str::uuid(),
            'name' => 'acxell',
            'nip' => '200301102025011002',
            'email' => 'acxell@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'id_organization' => $organization->id,
        ]);
        $acxell->assignRole($perguruanTinggiRole);

        $nopal = User::create([
            'id' => Str::uuid(),
            'name' => 'nopal',
            'nip' => '200301102025011003',
            'email' => 'nopal@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => 1,
            'id_organization' => $organization->id,
        ]);
        $nopal->assignRole($badanPenyelenggaraRole);
    }
}
