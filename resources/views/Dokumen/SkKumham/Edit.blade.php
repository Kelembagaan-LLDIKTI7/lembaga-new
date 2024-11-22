@extends('Layouts.Main')

@section('title', 'Edit SK Kumham')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formKumhamEdit" action="{{ route('sk-kumham.update', $skKumham->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_akta" value="{{ $skKumham->id_akta }}" class="form-control" required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Update SK Kumham</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_nomor" class="required-label">No Kumham</label>
                                        <input type="text" class="form-control" id="kumham_nomor" name="kumham_nomor"
                                            value="{{ old('kumham_nomor', $skKumham->kumham_nomor) }}" required>
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
                                            value="{{ old('kumham_tanggal', $skKumham->kumham_tanggal) }}" required>
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
                                            value="{{ old('kumham_perihal', $skKumham->kumham_perihal) }}" required>
                                        @error('kumham_perihal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kumham_perihal"></small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="kumham_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="kumham_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        <div id="file-preview">
                                            @if ($skKumham->kumham_dokumen)
                                                <p>File sebelumnya: <a
                                                        href="{{ asset('storage/' . $skKumham->kumham_dokumen) }}"
                                                        target="_blank">Lihat dokumen</a></p>
                                                @if (pathinfo($skKumham->kumham_dokumen, PATHINFO_EXTENSION) === 'pdf')
                                                    <iframe src="{{ asset('storage/' . $skKumham->kumham_dokumen) }}"
                                                        width="100%" height="400px"></iframe>
                                                @endif
                                            @endif
                                        </div>
                                        @error('kumham_dokumen')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kumham_dokumen"></small>
                                    </div>
                                </div>

                                <div class="btn-center mt-3">
                                    <div id="buttons">
                                        <a href="{{ route('badan-penyelenggara.show', ['id' => $akta->id_organization]) }}"
                                            class="btn btn-primary btn-sm-custom">Keluar</a>
                                        <button type="submit" class="btn btn-primary btn-sm-custom">Simpan</button>
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
            $('#formKumhamEdit').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide(); // Sembunyikan tombol
                $('#loading').show(); // Tampilkan loading

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('sk-kumham.validationUpdate', ['id' => $skKumham->id]) }}',
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
                    url: '{{ route('sk-kumham.update', ['id' => $skKumham->id]) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
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
