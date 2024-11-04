@extends('Layouts.Main')

@section('title', 'Tambah Badan Penyelenggara')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formBP" action="{{ route('badan-penyelenggara.update', ['id' => $bp->id]) }}" method="POST"
                    enctype="multipart/form-data" onsubmit="return validateForm()">
                    @csrf
                    @method('PUT')
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Edit Badan Penyelenggara</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Badan Penyelenggara</label>
                                        <input type="text" name="organisasi_nama" class="form-control"
                                            value="{{ $bp->organisasi_nama }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control"
                                            value="{{ $bp->organisasi_nama_singkat }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Badan
                                            Penyelenggara</label>
                                        <input type="email" name="organisasi_email" class="form-control"
                                            value="{{ $bp->organisasi_email }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control"
                                            value="{{ $bp->organisasi_telp }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control"
                                            value="{{ $bp->organisasi_alamat }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control"
                                            value="{{ $bp->organisasi_website }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}"
                                                    {{ $bp->organisasi_kota == $kota->nama ? 'selected' : '' }}>
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Perguruan Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control"
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('badan-penyelenggara.show', ['id' => $bp->id]) }}"
                        class="btn btn-secondary">Keluar</a>
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
    </script>
@endsection
