@extends('Layouts.Main')

@section('title', 'SK Kumham')

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('badan-penyelenggara.show', ['id' => $akta->id_organization]) }}"><i
                class="fas fa-arrow-left mb-4 me-2"></i> Kembali</a>
        <div class="row">
            <div class="col-12">
                <form id="formKumham" action="{{ route('sk-kumham.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_akta" value="{{ $akta->id }}" class="form-control" required>
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Form Tambah SK Kumham</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_nomor" class="required-label">No Kumham</label>
                                        <input type="text" class="form-control" id="kumham_nomor" name="kumham_nomor"
                                            value="" required>
                                        @error('kumham_nomor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kumham_nomor"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kumham_tanggal" class="required-label">Tanggal Kumham</label>
                                        <input type="date" class="form-control" id="kumham_tanggal" name="kumham_tanggal"
                                            value="" required>
                                        @error('kumham_tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kumham_tanggal"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_perihal" class="required-label">Perihal</label>
                                        <input type="text" class="form-control" id="kumham_perihal" name="kumham_perihal"
                                            value="" required>
                                        @error('kumham_perihal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kumham_perihal"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="kumham_dokumen" class="form-control" required
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX. Maksimal Ukuran File : 2 MB.</small>
                                        <div id="file-preview"></div>
                                    </div>
                                    <small class="text-danger error-message" id="error-kumham_dokumen"></small>
                                </div>

                                <div>
                                    <div id="buttons">
                                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                                    </div>
                                    <div id="loading" style="display: none;">
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
            // Sembunyikan elemen loading saat halaman dimuat
            $('#loading').hide();

            // Fungsi submit form
            $('#formKumham').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form
                $('#buttons').hide(); // Sembunyikan tombol
                $('#loading').show(); // Tampilkan elemen loading

                // Ambil data form
                const formData = new FormData(this);

                // Validasi input via AJAX
                $.ajax({
                    url: '{{ route('sk-kumham.validationStore') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            submitToStore(formData);
                        } else {
                            displayErrors(response.errors);
                            $('#loading').hide();
                            $('#buttons').show();
                        }
                    },
                    error: function(xhr) {
                        $('#error-messages').html('Terjadi kesalahan pada server. Coba lagi.');
                        $('#loading').hide();
                        $('#buttons').show();
                    }
                });
            });

            // Fungsi untuk menampilkan error validasi
            function displayErrors(errors) {
                $('.error-message').text(''); // Bersihkan pesan error sebelumnya
                for (let field in errors) {
                    $(`#error-${field}`).text(errors[field].join(', '));
                }
            }

            // Submit data ke server
            function submitToStore(formData) {
                $.ajax({
                    url: '{{ route('sk-kumham.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect; // Redirect ke URL
                        }
                    },
                    error: function(xhr) {
                        $('#error-messages').html(
                            'Terjadi kesalahan pada server saat penyimpanan. Coba lagi.');
                        $('#loading').hide();
                        $('#buttons').show();
                    }
                });
            }
        });

        // Fungsi preview file
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
                } else {
                    const unsupportedMessage = document.createElement('p');
                    unsupportedMessage.textContent = 'Preview hanya tersedia untuk file PDF.';
                    previewContainer.appendChild(unsupportedMessage);
                }
            }
        }
    </script>
@endsection
