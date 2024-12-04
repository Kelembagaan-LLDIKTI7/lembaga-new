@extends('Layouts.Main')
@section('title', 'Kelembagaan - Edit User')
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
                                <h5>Form Edit User</h5>
                                <form action="{{ route('user.update', $user->id) }}" method="POST" class="row g-3"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-6">
                                        <label for="name" class="required-label">Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="required-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="passwordInput" name="password">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <small class="form-text text-muted">Jika tidak mengisi password maka akan menggunakan password lama.
                            Minimal 10 Karakter pada password</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nip" class="required-label">Nomor Induk Pegawai</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                            name="nip" value="{{ old('nip', $user->nip) }}">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                                    <div class="col-md-6">
                                        <label for="is_active" class="required-label">Status Aktif</label>
                                        <select class="form-control @error('is_active') is-invalid @enderror" id="is_active"
                                            name="is_active" required>
                                            <option value="">Pilih Status</option>
                                            <option value="1"
                                                {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0"
                                                {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="roles" class="required-label">Role User</label>
                                        <select class="form-control @error('roles') is-invalid @enderror" id="roles"
                                            name="roles[]" multiple required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}"
                                                    {{ in_array($role, old('roles', $userRoles)) ? 'selected' : '' }}>
                                                    {{ $role }}
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
                                    {{ $item->organisasi_nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-primary mb-1 me-1" type="submit">Simpan</button>
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
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' :
                    '<i class="bi bi-eye"></i>';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rolesElement = document.getElementById('roles');
            new Choices(rolesElement, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Pilih Role...',
                maxItemCount: -1,
            });

            const organizationsElement = document.getElementById('id_organizations');
            new Choices(organizationsElement, {
                removeItemButton: false,
                placeholder: true,
                placeholderValue: 'Pilih Organisasi...',
                searchEnabled: true,
                searchFields: ['label'],
                searchResultLimit: 10,
                fuseOptions: {
                    threshold: 0.5,
                    minMatchCharLength: 1,
                },
            });
        });
    </script>
@endsection
