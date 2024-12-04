@extends('Layouts.Main')

@section('title', 'Tambah Badan Penyelenggara')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('badan-penyelenggara.index') }}"><i class="fas fa-arrow-left mb-4 me-2"></i>Kembali</a>
        <div class="row">
            <div class="col-12">
                <form id="formBP" action="{{ route('badan-penyelenggara.store') }}" method="POST"
                    enctype="multipart/form-data" onsubmit="return validateForm()">
                    @csrf
                    <div class="card mb-4 bordered">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Badan Penyelenggara</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Badan Penyelenggara</label>
                                        <input type="text" id="organisasi_nama" name="organisasi_nama"
                                            class="form-control" value="{{ old('organisasi_nama') }}" required>
                                        @error('organisasi_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_nama"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama">Nama Singkat Organisasi</label>
                                        <input type="text" id="organisasi_nama_singkat" name="organisasi_nama_singkat"
                                            class="form-control" value="{{ old('organisasi_nama_singkat') }}">
                                        @error('organisasi_nama_singkat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_nama_singkat"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_email">Email Badan
                                            Penyelenggara</label>
                                        <input type="email" id="organisasi_email" name="organisasi_email"
                                            class="form-control" value="{{ old('organisasi_email') }}">
                                        @error('organisasi_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_email"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp">No Telepon</label>
                                        <input type="text" id="organisasi_telp" name="organisasi_telp"
                                            class="form-control" value="{{ old('organisasi_telp') }}">
                                        @error('organisasi_telp')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_telp"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" id="organisasi_alamat" name="organisasi_alamat"
                                            class="form-control" value="{{ old('organisasi_alamat') }}" required>
                                        @error('organisasi_alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_alamat"></small>
                                    </div>

                                    <div class="form-group mb-3 d-flex gap-1">
                                        <input type="checkbox" id="isChangeBP" name="isChangeBP" value="1"
                                            onchange="toggleSelect()">
                                        <label for="isChangeBP">Apakah perubahan dari BP lama?</label>
                                    </div>

                                    <div class="form-group mb-3" id="selectBPContainer" style="display: none;">
                                        <label for="selectedBP" class="required-label">Pilih Badan Penyelenggara
                                            Lama</label>
                                        <select name="selectedBP" id="selectedBP" class="form-control select-search">
                                            <option value="">-- Pilih Badan Penyelenggara --</option>
                                            @foreach ($badanPenyelenggaras as $badan)
                                                <option value="{{ $badan->id }}"
                                                    @if (old('selectedBP') == $badan->id) selected @endif>
                                                    {{ $badan->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger error-message" id="error-selectedBP"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website</label>
                                        <input type="text" id="organisasi_website" name="organisasi_website"
                                            class="form-control" value="{{ old('organisasi_website') }}">
                                        @error('organisasi_website')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_website"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select id="organisasi_kota" name="organisasi_kota"
                                            class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}"
                                                    @if (old('organisasi_kota') == $kota->nama) selected @endif>
                                                    {{ $kota->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('organisasi_kota')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_kota"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo">Logo Badan Penyelenggara</label>
                                        <input type="file" id="organisasi_logo" name="organisasi_logo"
                                            class="form-control" accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF. Maksimal Ukuran File : 2 MB.</small>
                                        @error('organisasi_logo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_logo"></small>
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bordered mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Masukkan Data Akta</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01" class="required-label">No Akta</label>
                                        <input type="text" class="form-control" id="akta_nomor" name="akta_nomor"
                                            value="{{ old('akta_nomor') }}" required>
                                        @error('akta_nomor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_nomor"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02" class="required-label">Tanggal Akta</label>
                                        <input type="date" class="form-control" id="akta_tanggal" name="akta_tanggal"
                                            data-provider="flatpickr" data-date-format="d M, Y"
                                            value="{{ old('akta_tanggal') }}" required>
                                        @error('akta_tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_tanggal"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="required-label">Nama Notaris</label>
                                        <input type="text" class="form-control" id="akta_nama_notaris"
                                            name="akta_nama_notaris" value="{{ old('akta_nama_notaris') }}" required>
                                        @error('akta_nama_notaris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_nama_notaris"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kotaAkta" class="required-label">Kota Notaris</label>
                                        <select id="kotaAkta" name="kotaAkta" class="form-control select-search"
                                            required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}"
                                                    @if (old('kotaAkta') == $kota->nama) selected @endif>
                                                    {{ $kota->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kotaAkta')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-kotaAkta"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akta_jenis" class="required-label">Jenis Akta</label>
                                        <select class="form-control select-search" id="akta_jenis" name="akta_jenis"
                                            required>
                                            <option value="">Jenis</option>
                                            <option value="Pendirian" @if (old('akta_jenis') == 'Pendirian') selected @endif>
                                                Pendirian</option>
                                            <option value="Perubahan" @if (old('akta_jenis') == 'Perubahan') selected @endif>
                                                Perubahan</option>
                                        </select>
                                        @error('akta_jenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akta_jenis"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                        <label for="aktaDokumen" class="required-label">Dokumen Akta</label>
                                        <input type="text" class="form-control" id="validationCustom03"
                                            name="aktaDokumen" value="{{ old('aktaDokumen') }}">
                                        @error('aktaDokumen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-aktaDokumen"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-end">Simpan</button>
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
            $('#formBP').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('badan-penyelenggara.validationStore') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            submitToStore(formData);
                        } else {
                            displayErrors(response.errors);
                        }
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
                    url: '{{ route('badan-penyelenggara.store') }}',
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
        function previewLogo(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('logo-preview');
                output.src = reader.result;
                output.style.display = 'block';
                output.style.maxWidth = '200px'; // Batas maksimum lebar pratinjau
                output.style.maxHeight = '200px'; // Batas maksimum tinggi pratinjau
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function previewPdf(event) {
            var file = event.target.files[0];
            if (file.type === "application/pdf") {
                var existingPreview = document.getElementById('pdf-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }

                var pdfPreview = document.createElement("embed");
                pdfPreview.id = 'pdf-preview';
                pdfPreview.src = URL.createObjectURL(file);
                pdfPreview.type = "application/pdf";
                pdfPreview.style.width = "100%";
                pdfPreview.style.maxWidth = "600px"; // Batas lebar maksimum pratinjau
                pdfPreview.style.height = "400px"; // Batas tinggi maksimum pratinjau

                // Menambahkan pratinjau di bawah input file
                event.target.parentElement.appendChild(pdfPreview);
            }
        }

        function toggleSelect() {
            var checkbox = document.getElementById('isChangeBP');
            var selectContainer = document.getElementById('selectBPContainer');
            var selectInput = document.getElementById('selectedBP');

            if (checkbox.checked) {
                selectContainer.style.display = 'block';
            } else {
                selectContainer.style.display = 'none';
                selectInput.value = ''; // Hapus nilai jika checkbox tidak dicentang
            }
        }

        function validateForm() {
            var checkbox = document.getElementById('isChangeBP');
            var selectInput = document.getElementById('selectedBP');

            if (checkbox.checked && selectInput.value === '') {
                alert('Silakan pilih Badan Penyelenggara Lama jika opsi diubah dicentang.');
                return false; // Mencegah pengiriman form
            }
            return true; // Melanjutkan pengiriman form
        }
    </script>
@endsection
