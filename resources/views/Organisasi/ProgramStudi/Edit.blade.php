@extends('Layouts.Main')

@section('title', 'Edit Program Studi')

@section('css')
    <style>
        input:focus {
            border-color: #66afe9;
            outline: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formProdiEdit" action="{{ route('program-studi.update', $prodi->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Program Studi</h5>

                            @if ($errors->any())
                                <div class="alert alert-danger" id="error-messages">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3 col-md-4">
                                        <label for="validationCustom01" class="form-label">Kode Program Studi</label>
                                        <input type="text" class="form-control" id="prodi_kode" name="prodi_kode"
                                            value="{{ old('prodi_kode', $prodi->prodi_kode) }}">
                                        @if ($errors->has('prodi_kode'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('prodi_kode') }}
                                            </div>
                                        @endif
                                        @error('prodi_kode')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_kode"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="prodi_nama" class="required-label">Nama Program Studi</label>
                                        <input type="text" value="{{ old('prodi_nama', $prodi->prodi_nama) }}"
                                            name="prodi_nama" class="form-control" required>
                                        @if ($errors->has('prodi_nama'))
                                            <span class="text-danger">{{ $errors->first('prodi_nama') }}</span>
                                        @endif
                                        @error('prodi_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_nama"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="prodi_active_status" class="required-label">Status Program</label>
                                        <select name="prodi_active_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Aktif"
                                                {{ old('prodi_active_status', $prodi->prodi_active_status) == 'Aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="Tidak Aktif"
                                                {{ old('prodi_active_status', $prodi->prodi_active_status) == 'Tidak Aktif' ? 'selected' : '' }}>
                                                Tidak
                                                Aktif</option>
                                        </select>
                                        @if ($errors->has('prodi_active_status'))
                                            <span class="text-danger">{{ $errors->first('prodi_active_status') }}</span>
                                        @endif
                                        @error('prodi_active_status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_active_status"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="prodi_jenjang" class="required-label">Program</label>
                                        <select name="prodi_jenjang" class="form-control select-search" required>
                                            <option value="">-- Pilih Program --</option>
                                            <option value="D1"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'D1' ? 'selected' : '' }}>
                                                D1
                                            </option>
                                            <option value="D2"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'D2' ? 'selected' : '' }}>
                                                D2
                                            </option>
                                            <option value="D3"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'D3' ? 'selected' : '' }}>
                                                D3
                                            </option>
                                            <option value="D4"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'D4' ? 'selected' : '' }}>
                                                D4
                                            </option>
                                            <option value="S1"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'S1' ? 'selected' : '' }}>
                                                S1
                                            </option>
                                            <option value="S2"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'S2' ? 'selected' : '' }}>
                                                S2
                                            </option>
                                            <option value="S3"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'S3' ? 'selected' : '' }}>
                                                S3
                                            </option>
                                            <option value="Profesi"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'Profesi' ? 'selected' : '' }}>
                                                Profesi
                                            </option>
                                            <option value="Sp1"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'Sp1' ? 'selected' : '' }}>
                                                Sp1
                                            </option>
                                            <option value="Sp2"
                                                {{ old('prodi_jenjang', $prodi->prodi_jenjang) == 'Sp2' ? 'selected' : '' }}>
                                                Sp2
                                            </option>
                                        </select>
                                        @if ($errors->has('prodi_jenjang'))
                                            <span class="text-danger">{{ $errors->first('prodi_jenjang') }}</span>
                                        @endif
                                        @error('prodi_jenjang')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_jenjang"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('program-studi.show', ['id' => $prodi->id]) }}"
                                class="btn btn-secondary">Keluar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#loading').hide();
            $('#formProdiEdit').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('program-studi.validationUpdate', ['id' => $prodi->id]) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            submitToStore(formData);
                        } else {
                            $('#loading').hide();
                            $('#buttons').show();
                            displayErrors(response.errors);
                        }
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        $('#buttons').show();
                        $('#error-messages').html('Terjadi kesalahan pada server. Coba lagi.');
                    }
                });
            });

            function displayErrors(errors) {
                // Bersihkan semua pesan error sebelumnya
                $('.error-message').text('');

                // Tampilkan pesan error baru
                for (let field in errors) {
                    const errorMessages = errors[field].join(
                        ', '); // Gabungkan pesan error jika ada lebih dari satu
                    $(`#error-${field}`).text(
                        errorMessages); // Tempatkan pesan error di elemen dengan id yang sesuai
                }
            }

            function submitToStore(formData) {
                $.ajax({
                    url: '{{ route('program-studi.update', ['id' => $prodi->id]) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect_url;
                        }
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        $('#buttons').show();
                        $('#error-messages').html(
                            'Terjadi kesalahan pada server saat penyimpanan. Coba lagi.');
                    }
                });
            }

        });
    </script>
@endsection
