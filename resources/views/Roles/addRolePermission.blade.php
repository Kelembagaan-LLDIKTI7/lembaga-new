@extends('Layouts.Main')
@section('title', 'Kelembagaan - Tambah Permission pada Role')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card bordered">
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-end mb-4">
                                    <h5 >Form Tambah Permission pada Role</h5>
                                    <h3 class="text-primary">{{ $role->name }}</h3>
                                </div>
                                <form method="POST" action="{{ route('addRolePermission.store', $role->id) }}">
                                    @csrf

                                    <!-- Permission Categories Start -->
                                    <div class="row">
                                        @php
                                            $categories = [
                                                'Badan Penyelenggara' => [
                                                    'Create Badan Penyelenggara',
                                                    'Edit Badan Penyelenggara',
                                                    'Delete Badan Penyelenggara',
                                                    'View Badan Penyelenggara',
                                                    'Import Badan Penyelenggara',
                                                    'Detail Badan Penyelenggara',
                                                ],
                                                'Pimpinan Badan Penyelenggara' => [
                                                    'Create Pimpinan Badan Penyelenggara',
                                                    'Edit Pimpinan Badan Penyelenggara',
                                                    'Delete Pimpinan Badan Penyelenggara',
                                                    'View Pimpinan Badan Penyelenggara',
                                                    'Detail Pimpinan Badan Penyelenggara',
                                                    'View PDF Pimpinan Badan Penyelenggara',
                                                ],
                                                'Akta' => [
                                                    'Create Akta Badan Penyelenggara',
                                                    'Edit Akta Badan Penyelenggara',
                                                    'Delete Akta Badan Penyelenggara',
                                                    'View Akta Badan Penyelenggara',
                                                    'Detail Akta Badan Penyelenggara',
                                                    'View PDF Akta Badan Penyelenggara',
                                                ],
                                                'SK Kumham' => [
                                                    'Create SK Kumham Badan Penyelenggara',
                                                    'Edit SK Kumham Badan Penyelenggara',
                                                    'Delete SK Kumham Badan Penyelenggara',
                                                    'View SK Kumham Badan Penyelenggara',
                                                    'Detail SK Kumham Badan Penyelenggara',
                                                    'View PDF SK Kumham Badan Penyelenggara',
                                                ],
                                                'Surat Keputusan Badan Penyelenggara' => [
                                                    'Create SK Badan Penyelenggara',
                                                    'Edit SK Badan Penyelenggara',
                                                    'View PDF Badan Penyelenggara',
                                                ],
                                                'Perguruan Tinggi' => [
                                                    'Create Perguruan Tinggi',
                                                    'Edit Perguruan Tinggi',
                                                    'Delete Perguruan Tinggi',
                                                    'View Perguruan Tinggi',
                                                    'Import Perguruan Tinggi',
                                                    'Detail Perguruan Tinggi',
                                                ],
                                                'Program Studi' => [
                                                    'Create Program Studi',
                                                    'Edit Program Studi',
                                                    'View Program Studi',
                                                    'Detail Program Studi',
                                                ],
                                                'Akreditasi Program Studi' => [
                                                    'Create Akreditasi Program Studi',
                                                    'Edit Akreditasi Program Studi',
                                                    'View Akreditasi Program Studi',
                                                    'Detail Akreditasi Program Studi',
                                                    'View PDF Akreditasi Program Studi',
                                                ],
                                                'Akreditasi Perguruan Tinggi' => [
                                                    'Create Akreditasi Perguruan Tinggi',
                                                    'Edit Akreditasi Perguruan Tinggi',
                                                    'Delete Akreditasi Perguruan Tinggi',
                                                    'View Akreditasi Perguruan Tinggi',
                                                    'Detail Akreditasi Perguruan Tinggi',
                                                    'View PDF Akreditasi Perguruan Tinggi',
                                                ],
                                                'Pimpinan Perguruan Tinggi' => [
                                                    'Create Pimpinan Perguruan Tinggi',
                                                    'Edit Pimpinan Perguruan Tinggi',
                                                    'Delete Pimpinan Perguruan Tinggi',
                                                    'View Pimpinan Perguruan Tinggi',
                                                    'Detail Pimpinan Perguruan Tinggi',
                                                    'View PDF Pimpinan Perguruan Tinggi',
                                                ],
                                                'Surat Keputusan Perguruan Tinggi' => [
                                                    'Create SK Perguruan Tinggi',
                                                    'Edit SK Perguruan Tinggi',
                                                    'Delete SK Perguruan Tinggi',
                                                    'View SK Perguruan Tinggi',
                                                    'Detail SK Perguruan Tinggi',
                                                    'View PDF SK Perguruan Tinggi',
                                                ],
                                                'Surat Keputusan Program Studi' => [
                                                    'Create SK Program Studi',
                                                    'Edit SK Program Studi',
                                                    'Delete SK Program Studi',
                                                    'View SK Program Studi',
                                                    'Detail SK Program Studi',
                                                    'View PDF SK Program Studi',
                                                ],
                                                'Jabatan' => [
                                                    'Create Jabatan',
                                                    'Edit Jabatan',
                                                    'Delete Jabatan',
                                                    'View Jabatan',
                                                ],
                                                'General Permissions' => [
                                                    'Create Permission',
                                                    'Edit Permission',
                                                    'Delete Permission',
                                                    'View Permission',
                                                ],
                                                'Role Management' => [
                                                    'Create Roles',
                                                    'Edit Roles',
                                                    'Delete Roles',
                                                    'View Roles',
                                                    'View Role Permissions',
                                                    'Add Role Permissions',
                                                ],
                                                'User Management' => [
                                                    'Create User',
                                                    'Edit User',
                                                    'Delete User',
                                                    'View User',
                                                ],
                                                'Peringkat Akreditasi' => [
                                                    'View Peringkat Akreditasi',
                                                    'Create Peringkat Akreditasi',
                                                    'Edit Peringkat Akreditasi',
                                                    'Delete Peringkat Akreditasi',
                                                ],
                                                'Lembaga Akreditasi' => [
                                                    'View Lembaga Akreditasi',
                                                    'Create Lembaga Akreditasi',
                                                    'Edit Lembaga Akreditasi',
                                                    'Delete Lembaga Akreditasi',
                                                ],
                                                'Jenis Organisasi' => [
                                                    'View Jenis Organisasi',
                                                    'Create Jenis Organisasi',
                                                    'Edit Jenis Organisasi',
                                                    'Delete Jenis Organisasi',
                                                ],
                                                'Perkara Badan Penyelenggara' => [
                                                    'Create Perkara Badan Penyelenggara',
                                                    'View Detail Perkara Badan Penyelenggara',
                                                    'Update Status Perkara Badan Penyelenggara',
                                                ],
                                                'Perkara Perguruan Tinggi' => [
                                                    'Create Perkara Perguruan Tinggi',
                                                    'View Detail Perkara Perguruan Tinggi',
                                                    'Update Status Perkara Perguruan Tinggi',
                                                ],
                                                'Perkara Program Studi' => [
                                                    'Create Perkara Program Studi',
                                                    'View Detail Perkara Program Studi',
                                                    'Update Status Perkara Program Studi',
                                                ],
                                                'Dashboard' => ['View Detail Perkara'],
                                            ];
                                        @endphp

                                        @foreach ($categories as $category => $permissionNames)
                                            <div class="col-md-4">
                                                <div class="card bordered mb-4">
                                                    <div class="card-header">{{ $category }}</div>
                                                    <div class="card-body">
                                                        <div class="list-group">
                                                            @foreach ($permissionNames as $permissionName)
                                                                @php
                                                                    $permission = $permissions->firstWhere(
                                                                        'name',
                                                                        $permissionName,
                                                                    );
                                                                @endphp
                                                                @if ($permission)
                                                                    <div class="form-check form-switch">
                                                                        <input type="checkbox" class="form-check-input"
                                                                            id="permission_{{ $permission->id }}"
                                                                            name="permission[]"
                                                                            value="{{ $permission->id }}"
                                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Permission Categories End -->

                                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
