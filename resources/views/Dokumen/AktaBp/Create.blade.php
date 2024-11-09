@extends('Layouts.Main')

@section('title', 'Tambah Akta')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formAkta" action="{{ route('akta-badan-penyelenggara.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_organization" value="{{ $bp->id }}" class="form-control" required>
                    <div class="card">
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
                                        <label for="akta_keterangan" class="required-label">Keterangan Akta</label>
                                        <textarea class="form-control" id="akta_keterangan" name="akta_keterangan">{{ old('akta_keterangan') }}</textarea>
                                        @error('akta_keterangan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_keterangan"></small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="aktaDokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="aktaDokumen" class="form-control" required
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        @error('aktaDokumen')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-aktaDokumen"></small>
                                        <div id="file-preview"></div>
                                    </div>
                                </div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('badan-penyelenggara.show', ['id' => $bp->id]) }}"
                                        class="btn btn-primary btn-sm-custom">Keluar</a>
                                    <button type="submit" class="btn btn-primary btn-sm-custom">Simpan</button>
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
            $('#formAkta').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

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
                        console.log(response);
                        // if (response.success) {
                        //     submitToStore(formData);
                        // } else {
                        //     displayErrors(response.errors);
                        // }
                    },
                    error: function(xhr) {
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
                        $('#error-messages').html(
                            'Terjadi kesalahan pada server saat penyimpanan. Coba lagi.');
                    }
                });
            }

        });
    </script>
    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('file-preview');
            previewContainer.innerHTML = '';

            if (file) {
                const fileName = document.createElement('p');
                fileName.textContent = `File terpilih: ${file.name}`;
                previewContainer.appendChild(fileName);

                if (file.type === 'application/pdf') {
                    const fileURL = URL.createObjectURL(file);
                    const iframe = document.createElement('iframe');
                    iframe.src = fileURL;
                    iframe.width = '100%';
                    iframe.height = '400px';
                    previewContainer.appendChild(iframe);
                }
            }
        }
    </script>
@endsection
