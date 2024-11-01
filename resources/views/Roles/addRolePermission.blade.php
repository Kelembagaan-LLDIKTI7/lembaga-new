@extends('layouts.main')
@section('title', 'Kelembagaan - Tambah Permission pada Role')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Form Tambah Permission pada Role</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                                <li class="breadcrumb-item active">Tambah Permission</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form method="POST" action="{{ route('addRolePermission.store', $role->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <h3>{{ $role->name }}</h3>
                    </div>
                </div>

                <!-- Permission Cards (Vertical list) Start -->
                <div class="row">
                    @foreach (['Akreditasi', 'Akta', 'Program Studi', 'Jabatan', 'Surat Keputusan', 'Perguruan Tinggi', 'Badan Penyelenggara', 'SK Kumham', 'User'] as $category)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">{{ $category }}</div>
                            <div class="card-body">
                                <div class="list-group">
                                    @foreach ($permissions as $permission)
                                    @if (Str::contains($permission->name, $category))
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="permission_{{ $permission->id }}" name="permission[]" value="{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Permission Cards End -->

                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection