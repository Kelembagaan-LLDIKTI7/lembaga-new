@extends('layouts.Main')
@section('title', 'Kelembagaan - Edit User')
@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Form Edit User</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form action="{{ route('user.update', $user->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="validationCustom01" name="name" value="{{ old('name') ?? $user->name }}" required>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">Email</label>
                    <input type="email" class="form-control" id="validationCustom01" name="email" value="{{ old('email') ?? $user->email }}" required>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">Password</label>
                    <input type="password" class="form-control" id="validationCustom01" name="password">
                </div>
                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">Nomor Induk Pegawai</label>
                    <input type="text" class="form-control" id="validationCustom01" name="nip" value="{{ old('nip') ?? $user->nip }}" required>
                </div>
                <div class="col-md-6">
                    <label for="is_active" class="form-label">Status Aktif</label>
                    <select class="form-control" id="is_active" data-choices name="is_active" required>
                        <option value="">Pilih Status</option>
                        <option value="1" {{ (old('is_active') ?? $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ (old('is_active') ?? $user->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="RolesLabel" class="form-label">Role User</label>
                    <select class="form-control" id="roles" data-choices data-choices-removeItem multiple name="roles[]" required>
                        @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ in_array($role, $userRoles) ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="OrganizationLabel" class="form-label">Organisasi User</label>
                    <select class="form-control" id="id_organizations" data-choices name="id_organization" required>
                        <option value="">Organisasi</option>
                        @foreach ($organization as $item)
                        <option value="{{ $item->id }}" {{ (old('id_organization') ?? $user->id_organization) == $item->id ? 'selected' : '' }}>{{ $item->organisasi_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </div>
            </form>


        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    {{-- <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Kelembagaan LLDIKTI Wilayah VII
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer> --}}
</div>
@endsection
