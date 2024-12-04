@extends('Layouts.Main')
@section('title', 'Kelembagaan - Tambah User')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <button onclick="window.history.back();" class="btn btn-link p-0 text-decoration-none"><i
                        class="fas fa-arrow-left mb-4 me-2"></i> Kembali
                </button>
                <div class="row">
                    <div class="col-12">
                        <div class="card bordered">
                            <div class="card-body">
                                <h5>Form Tambah User</h5>
                                <form action="{{ route('user.store') }}" method="POST" class="row g-3"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="required-label">Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="validationCustom01" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="required-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="validationCustom01" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="required-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="validationCustom01" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="required-label">Nomor Induk Pegawai</label>
                                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                            id="validationCustom01" name="nip" value="{{ old('nip') }}" required>
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="is_active" class="required-label">Status Aktif</label>
                                        <select class="form-control @error('is_active') is-invalid @enderror" id="is_active"
                                            data-choices name="is_active" required>
                                            <option value="">Pilih Status</option>
                                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="RolesLabel" class="required-label">Role User</label>
                                        <select class="form-control @error('roles') is-invalid @enderror" id="roles"
                                            name="roles[]" multiple required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}"
                                                    {{ collect(old('roles'))->contains($role) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label for="OrganizationLabel" class="required-label">Organisasi User</label>
                                        <select class="form-control @error('id_organization') is-invalid @enderror"
                                            id="id_organizations" name="id_organization" required>
                                            @foreach ($organization as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('id_organization') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_organization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-primary me-1 mb-1" type="submit">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                maxItemCount: 1,
                searchResultLimit: 5,
                searchFields: ['label'],
                fuseOptions: {
                    threshold: 0.3,
                    minMatchCharLength: 2,
                }
            });
        });
    </script>
@endsection
