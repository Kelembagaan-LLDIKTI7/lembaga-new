@extends('Layouts.Main')

@section('title', 'Edit Akreditasi Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('perguruan-tinggi.show', ['id' => $akreditasi->id_organization]) }}">
            <i class="fas fa-arrow-left mb-4 me-2"></i> Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <form id="formAkreditasiPTedit" action="{{ route('akreditasi-perguruan-tinggi.update', $akreditasi->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Menggunakan metode PUT untuk update -->
                    <input type="hidden" name="id_organization" value="{{ $akreditasi->id_organization }}"
                        class="form-control" required>
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Form Edit Akreditasi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akreditasi_sk" class="required-label">Nomor Surat Keputusan
                                            Akreditasi</label>
                                        <input type="text" name="akreditasi_sk" class="form-control"
                                            value="{{ old('akreditasi_sk', $akreditasi->akreditasi_sk) }}" required>
                                        @error('akreditasi_sk')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akreditasi_sk"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="akreditasi_tgl_awal" class="required-label">Tanggal Mulai
                                            Berlaku</label>
                                        <input type="date" name="akreditasi_tgl_awal" class="form-control"
                                            value="{{ old('akreditasi_tgl_awal', $akreditasi->akreditasi_tgl_awal) }}"
                                            required>
                                        @error('akreditasi_tgl_awal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akreditasi_tgl_awal"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_peringkat_akreditasi" class="required-label">Peringkat
                                            Akreditasi</label>
                                        <select name="id_peringkat_akreditasi" class="form-control select-search" required>
                                            <option value="">-- Pilih Peringkat --</option>
                                            @foreach ($peringkat as $peringkat)
                                                <option value="{{ $peringkat->id }}"
                                                    {{ $akreditasi->id_peringkat_akreditasi == $peringkat->id ? 'selected' : '' }}>
                                                    {{ $peringkat->peringkat_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_peringkat_akreditasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-id_peringkat_akreditasi"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akreditasi_status" class="required-label">Status Akreditasi</label>
                                        <select name="akreditasi_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Berlaku"
                                                {{ $akreditasi->akreditasi_status == 'Berlaku' ? 'selected' : '' }}>
                                                Berlaku
                                            </option>
                                            <option value="Dicabut"
                                                {{ $akreditasi->akreditasi_status == 'Dicabut' ? 'selected' : '' }}>
                                                Dicabut
                                            </option>
                                            <option value="Tidak Berlaku"
                                                {{ $akreditasi->akreditasi_status == 'Tidak Berlaku' ? 'selected' : '' }}>
                                                Tidak Berlaku</option>
                                        </select>
                                        @error('akreditasi_status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akreditasi_status"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="akreditasi_tgl_akhir" class="required-label">Tanggal Akhir
                                            Berlaku</label>
                                        <input type="date" name="akreditasi_tgl_akhir" class="form-control"
                                            value="{{ old('akreditasi_tgl_akhir', $akreditasi->akreditasi_tgl_akhir) }}"
                                            required>
                                        @error('akreditasi_tgl_akhir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-akreditasi_tgl_akhir"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_lembaga_akreditasi" class="required-label">Lembaga
                                            Akreditasi</label>
                                        <select name="id_lembaga_akreditasi" class="form-control select-search" required>
                                            <option value="">-- Pilih Lembaga --</option>
                                            @foreach ($lembaga as $lembaga)
                                                <option value="{{ $lembaga->id }}"
                                                    {{ $akreditasi->id_lembaga_akreditasi == $lembaga->id ? 'selected' : '' }}>
                                                    {{ $lembaga->lembaga_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_lembaga_akreditasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-id_lembaga_akreditasi"></small>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                    <input type="file" name="sk_dokumen" class="form-control" accept=".pdf,.doc,.docx"
                                        onchange="previewFile(event)">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                        DOCX.</small>
                                    <div id="file-preview" class="mt-3"></div> <!-- Tempat untuk preview -->
                                    @error('sk_dokumen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-danger error-message" id="error-sk_dokumen"></small>
                                </div>

                                <div>
                                    <div id="buttons">
                                        @can('Detail Perguruan Tinggi')
                                        @endCan
                                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                                    </div>
                                    <div id="loading">
                                        <div class="d-flex align-items-center">
                                            <strong>Loading...</strong>
                                            <div class="spinner-border ms-auto" role="status" aria-hidden="true">
                                            </div>
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
            $('#formAkreditasiPTedit').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide(); // Sembunyikan tombol
                $('#loading').show(); // Tampilkan loading

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('akreditasi-perguruan-tinggi.validationUpdate', ['id' => $akreditasi->id]) }}',
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
                    url: '{{ route('akreditasi-perguruan-tinggi.update', ['id' => $akreditasi->id]) }}',
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
