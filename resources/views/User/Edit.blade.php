@extends('Layouts.Main')
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

                <form action="{{ route('user.update', $user->id) }}" method="POST" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="name" class="required-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="required-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        <small class="form-text text-muted">Jika tidak mengisi password maka akan menggunakan password
                            lama.</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nip" class="required-label">Nomor Induk Pegawai</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                            name="nip" value="{{ old('nip', $user->nip) }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="is_active" class="required-label">Status Aktif</label>
                        <select class="form-control @error('is_active') is-invalid @enderror" id="is_active"
                            name="is_active" required>
                            <option value="">Pilih Status</option>
                            <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Tidak
                                Aktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="roles" class="required-label">Role User</label>
                        <select class="form-control @error('roles') is-invalid @enderror" id="roles" name="roles[]"
                            multiple required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}"
                                    {{ in_array($role, old('roles', $userRoles)) ? 'selected' : '' }}>{{ $role }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="id_organizations" class="required-label">Organisasi User</label>
                        <select class="form-control @error('id_organization') is-invalid @enderror" id="id_organizations"
                            name="id_organization" required>
                            @foreach ($organization as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('id_organization', $user->id_organization) == $item->id ? 'selected' : '' }}>
                                    {{ $item->organisasi_nama }}</option>
                            @endforeach
                        </select>
                        @error('id_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-danger mb-1 me-1" onclick="window.history.back();">Go
                            Back</button>
                        <button class="btn btn-primary mb-1 me-1" type="submit">Submit form</button>
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

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('roles');
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih Role...',
                maxItemCount: -1,
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('id_organizations');
            const choices = new Choices(element, {
                removeItemButton: false,
                placeholder: true,
                placeholderValue: 'Organisasi',
                searchResultLimit: -1,
                searchFields: ['label'],
                fuseOptions: {
                    threshold: 0.3,
                    minMatchCharLength: 2,
                }
            });
        });
    </script>
@endsection
