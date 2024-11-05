@extends('Layouts.Main')

@section('title', 'Tambah Badan Penyelenggara')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formBP" action="{{ route('badan-penyelenggara.store') }}" method="POST"
                    enctype="multipart/form-data" onsubmit="return validateForm()">
                    @csrf
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Badan Penyelenggara</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Badan Penyelenggara</label>
                                        <input type="text" name="organisasi_nama" class="form-control" required>
                                        @error('organisasi_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control">
                                        @error('organisasi_nama_singkat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Badan
                                            Penyelenggara</label>
                                        <input type="email" name="organisasi_email" class="form-control" required>
                                        @error('organisasi_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control" required>
                                        @error('organisasi_telp')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control" required>
                                        @error('organisasi_alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="isChangeBP">Apakah perubahan dari BP lama?</label>
                                        <input type="checkbox" id="isChangeBP" name="isChangeBP" value="1"
                                            onchange="toggleSelect()">
                                    </div>

                                    <div class="form-group mb-3" id="selectBPContainer" style="display: none;">
                                        <label for="selectedBP" class="required-label">Pilih Badan Penyelenggara
                                            Lama</label>
                                        <select name="selectedBP" id="selectedBP" class="form-control select-search">
                                            <option value="">-- Pilih Badan Penyelenggara --</option>
                                            @foreach ($badanPenyelenggaras as $badan)
                                                <option value="{{ $badan->id }}">{{ $badan->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}">{{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Badan Penyelenggara</label>
                                        <input type="file" name="organisasi_logo" class="form-control" required
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        @error('organisasi_logo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Masukkan Data Akta</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01" class="required-label">No Akta</label>
                                        <input type="text" class="form-control" id="validationCustom01" name="akta_nomor"
                                            required>
                                        @error('akta_nomor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02" class="required-label">Tanggal Akta</label>
                                        <input type="date" class="form-control" id="validationCustom02"
                                            name="akta_tanggal" data-provider="flatpickr" data-date-format="d M, Y"
                                            required>
                                        @error('akta_tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="required-label">Nama Notaris</label>
                                        <input type="text" class="form-control" id="validationCustom03"
                                            name="akta_nama_notaris" required>
                                        @error('akta_nama_notaris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kotaAkta" class="required-label">Kota Notaris</label>
                                        <select name="kotaAkta" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}">{{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom04" class="required-label">Jenis Akta</label>
                                        <select class="form-control select-search" id="validationCustom04"
                                            name="akta_jenis" required>
                                            <option value="">Jenis</option>
                                            <option value="Pendirian">Pendirian</option>
                                            <option value="Perubahan">Perubahan</option>
                                        </select>
                                        @error('akta_jenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akta_keterangan" class="required-label">Keterangan Akta</label>
                                        <textarea class="form-control" id="akta_keterangan" name="akta_keterangan"></textarea>
                                        @error('akta_keterangan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="aktaDokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="aktaDokumen" class="form-control" required
                                            accept=".pdf,.doc,.docx" onchange="previewPdf(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        @error('aktaDokumen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('badan-penyelenggara.index') }}" class="btn btn-secondary">Keluar</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
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
