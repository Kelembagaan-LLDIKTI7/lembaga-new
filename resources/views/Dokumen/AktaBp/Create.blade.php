@extends('Layouts.Main')

@section('title', 'Tambah Akta')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('badan-penyelenggara.show', ['id' => $bp->id]) }}"> <i class="fas fa-arrow-left mb-4 me-2"></i>
            Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <form id="formAkta" action="{{ route('akta-badan-penyelenggara.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_organization" value="{{ $bp->id }}" class="form-control" required>
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title">Form Tambah Akta</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01" class="required-label">No Akta</label>
                                        <input type="text" class="form-control" id="validationCustom01" name="akta_nomor"
                                            value="{{ old('akta_nomor') }}" required>
                                        @error('akta_nomor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_nomor"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02" class="required-label">Tanggal Akta</label>
                                        <input type="date" class="form-control" id="validationCustom02"
                                            name="akta_tanggal" value="{{ old('akta_tanggal') }}" required>
                                        @error('akta_tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_tanggal"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="required-label">Nama Notaris</label>
                                        <input type="text" class="form-control" id="validationCustom03"
                                            name="akta_nama_notaris" value="{{ old('akta_nama_notaris') }}" required>
                                        @error('akta_nama_notaris')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_nama_notaris"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="aktaDokumen">Link Dokumen</label>
                                        <input type="text" class="form-control" id="validationCustom03"
                                            name="aktaDokumen" value="{{ old('aktaDokumen') }}">
                                        @error('aktaDokumen')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-aktaDokumen"></small>
                                        <div id="file-preview"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="aktaJenis" class="required-label">Jenis Akta</label>
                                        <select class="form-control select-search" id="aktaJenis" name="akta_jenis"
                                            required>
                                            <option value="">Jenis</option>
                                            <option value="Pendirian"
                                                {{ old('akta_jenis') == 'Pendirian' ? 'selected' : '' }}>Pendirian</option>
                                            <option value="Perubahan"
                                                {{ old('akta_jenis') == 'Perubahan' ? 'selected' : '' }}>Perubahan</option>
                                        </select>
                                        @error('akta_jenis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_jenis"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kotaAkta" class="required-label">Kota</label>
                                        <select name="kotaAkta" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kota as $k)
                                                <option value="{{ $k->nama }}"
                                                    {{ old('kotaAkta') == $k->nama ? 'selected' : '' }}>
                                                    {{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('kotaAkta')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kotaAkta"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="akta_keterangan">Keterangan Akta</label>
                                        <textarea class="form-control" id="akta_keterangan" name="akta_keterangan">{{ old('akta_keterangan') }}</textarea>
                                        @error('akta_keterangan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_keterangan"></small>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div id="buttons">
                                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                                    </div>
                                    <div id="loading">
                                        <div class="d-flex align-items-center">
                                            <strong>Loading...</strong>
                                            <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                                        </div>
                                    </div>
                                    <div id="error-messages"></div>
                                </div>
                            </div>
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
            $('#loading').hide(); // Sembunyikan loading
            $('#formAkta').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide(); // Sembunyikan tombol
                $('#loading').show(); // Tampilkan loading

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('akta-badan-penyelenggara.validationStore') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            submitToStore(formData);
                        } else {
                            $('#loading').hide(); // Sembunyikan loading
                            $('#buttons').show(); // Tampilkan tombol
                            displayErrors(response.errors);
                        }
                    },
                    error: function(xhr) {
                        $('#loading').hide(); // Sembunyikan loading
                        $('#buttons').show(); // Tampilkan tombol
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
                    url: '{{ route('akta-badan-penyelenggara.store') }}',
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
                        $('#loading').hide(); // Sembunyikan loading
                        $('#buttons').show(); // Tampilkan tombol
                        $('#error-messages').html(
                            'Terjadi kesalahan pada server saat penyimpanan. Coba lagi.');
                    }
                });
            }

        });
    </script>
@endsection
