@extends('Layouts.Main')

@section('title', 'Tambah Pimpinan Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('badan-penyelenggara.show', ['id' => $bp->id]) }}"><i class="fas fa-arrow-left mb-4 me-2"></i>
            Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <form id="formPimpinanBP" action="{{ route('pimpinan-badan-penyelenggara.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_organization" value="{{ $bp->id }}" class="form-control" required>
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title">Form Tambah Pimpinan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="pimpinan_nama" class="required-label">
                                            Nama Pimpinan
                                        </label>
                                        <input type="text" name="pimpinan_nama" class="form-control" required>
                                        @error('pimpinan_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-pimpinan_nama"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_email" class="required-label">
                                            Email Pimpinan
                                        </label>
                                        <input type="email" name="pimpinan_email" class="form-control" required>
                                        @error('pimpinan_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-pimpinan_email"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_sk" class="required-label">
                                            No SK Pimpinan
                                        </label>
                                        <input type="text" name="pimpinan_sk" class="form-control" required>
                                        @error('pimpinan_sk')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-pimpinan_sk"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="pimpinan_tanggal" class="required-label">
                                            Tanggal Terbit SK
                                        </label>
                                        <input type="date" name="pimpinan_tanggal" class="form-control" required>
                                        @error('pimpinan_tanggal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-pimpinan_tanggal"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_tanggal" class="required-label">
                                            Tanggal Berakhir SK
                                        </label>
                                        <input type="date" name="pimpinan_tanggal_berakhir" class="form-control"
                                            required>
                                        @error('pimpinan_tanggal_berakhir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message"
                                            id="error-pimpinan_tanggal_berakhir"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_jabatan" class="required-label">
                                            Jabatan Pimpinan
                                        </label>
                                        <select name="id_jabatan" class="form-control select-search">
                                            <option value="">-- Pilih Peringkat --</option>
                                            @foreach ($jabatan as $jabatan)
                                                <option value="{{ $jabatan->id }}">{{ $jabatan->jabatan_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jabatan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-id_jabatan"></small>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="pimpinan_sk_dokumen" class="required-label">Dokumen SK</label>
                                    <input type="file" name="pimpinan_sk_dokumen" class="form-control" required
                                        accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC, DOCX.</small>
                                    <div id="file-preview" class="mt-3"></div>
                                    @error('pimpinan_sk_dokumen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-danger error-message" id="error-pimpinan_sk_dokumen"></small>
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
            $('#loading').hide();
            $('#formPimpinanBP').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('pimpinan-badan-penyelenggara.validationStore') }}',
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
                    url: '{{ route('pimpinan-badan-penyelenggara.store') }}',
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
