@extends('Layouts.Main')

@section('title', 'Tambah Evaluasi Prodi')

@section('css')
    <style>
        .form-container {
            display: flex;
        }

        .form-left,
        .form-right {
            flex-basis: 50%;
            padding: 0 10px;
        }

        .image-preview {
            position: relative;
            display: inline-block;
            margin: 5px;
        }

        .image-preview img {
            width: 150px;
            height: auto;
        }

        .image-preview span {
            position: absolute;
            top: 0px;
            right: 5px;
            cursor: pointer;
            color: red;
            font-size: 24px;
            font-weight: bold;
            line-height: 15px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Tambah Evaluasi Prodi</h3>
                <form id="formPerkaraProdi" action="{{ route('evaluasi-prodi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_prodi" value="{{ $prodi->id }}" class="form-control" required>

                    <div class="form-container">
                        <div class="form-left">
                            <div class="mb-3">
                                <label for="title">Judul Evaluasi</label>
                                <input type="text" name="title" id="title" class="form-control" required
                                    value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="text-danger error-message" id="error-nomor"></small>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_kejadian">Tanggal Kejadian</label>
                                <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="form-control"
                                    required value="{{ old('tanggal_kejadian') }}">
                                @error('tanggal_kejadian')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="text-danger error-message" id="error-tanggal_kejadian"></small>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_kejadian">Deskripsi Kejadian</label>
                                <textarea name="deskripsi_kejadian" id="deskripsi_kejadian" class="form-control" required>{{ old('deskripsi_kejadian') }}</textarea>
                                @error('deskripsi_kejadian')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="text-danger error-message" id="error-deskripsi_kejadian"></small>
                            </div>
                        </div>

                        <div class="form-right">
                            <div class="mb-3">
                                <label for="no_perkara">Nomor Perkara</label>
                                <input type="text" name="no_perkara" id="no_perkara" class="form-control" required
                                    value="{{ old('no_perkara') }}">
                                @error('no_perkara')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="bukti_foto">Bukti Foto</label>
                                <input type="file" name="bukti_foto[]" id="bukti_foto" class="form-control" multiple
                                    accept="image/*">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                    GIF.</small>
                                @error('bukti_foto.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="text-danger error-message" id="error-bukti_foto"></small>
                            </div>
                            <div id="preview" class="preview-container"></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Keluar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                    <div id="error-messages" class="text-danger"></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#loading').hide();
            $('#formPerkaraProdi').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

            // AJAX request ke server untuk validasi
            $.ajax({
                url: '{{ route('evaluasi-prodi.validationStore') }}',
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
                url: '{{ route('evaluasi-prodi.store') }}',
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
        let selectedFiles = [];

        document.getElementById('bukti_foto').addEventListener('change', function(event) {
            selectedFiles = Array.from(event.target.files);
            renderPreviews();
        });

        function renderPreviews() {
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileReader = new FileReader();
                fileReader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('image-preview');
                    imgContainer.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail">
                        <span onclick="removeImage(${index})">&times;</span>
                    `;
                    previewContainer.appendChild(imgContainer);
                };
                fileReader.readAsDataURL(file);
            });

            updateFileInput();
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            renderPreviews();
        }

        function updateFileInput() {
            const fileInput = document.getElementById('bukti_foto');
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }
    </script>
@endsection
