@extends('Layouts.Main')

@section('title', 'Tambah Program Studi')

@section('css')
    <style>
        input:focus {
            border-color: #66afe9;
            outline: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formProdi" action="{{ route('program-studi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Program Studi</h5>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="id_organization" value="{{ $organisasi->id }}">
                                    <div class="form-group mb-3 col-md-4">
                                        <label for="validationCustom01" class="form-label">Kode Program Studi</label>
                                        <input type="text" class="form-control" id="prodi_kode" name="prodi_kode"
                                            value="{{ old('prodi_kode') }}">
                                        @if ($errors->has('prodi_kode'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('prodi_kode') }}
                                            </div>
                                        @endif
                                        @error('prodi_kode')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_kode"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="prodi_nama" class="required-label">Nama Program Studi</label>
                                        <input type="text" name="prodi_nama" class="form-control"
                                            value="{{ old('prodi_nama') }}" required>
                                        @if ($errors->has('prodi_nama'))
                                            <span class="text-danger">{{ $errors->first('prodi_nama') }}</span>
                                        @endif
                                        @error('prodi_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_nama"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="prodi_active_status" class="required-label">Status</label>
                                        <select name="prodi_active_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Aktif" @if (old('prodi_active_status') == 'Aktif') selected @endif>Aktif
                                            </option>
                                            <option value="Tidak Aktif" @if (old('prodi_active_status') == 'Tidak Aktif') selected @endif>
                                                Tidak Aktif</option>
                                        </select>
                                        @if ($errors->has('prodi_active_status'))
                                            <span class="text-danger">{{ $errors->first('prodi_active_status') }}</span>
                                        @endif
                                        @error('prodi_active_status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_active_status"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="prodi_jenjang" class="required-label">Program</label>
                                        <select name="prodi_jenjang" class="form-control select-search" required>
                                            <option value="">-- Pilih Program --</option>
                                            <option value="D1" @if (old('prodi_jenjang') == 'D1') selected @endif>D1
                                            </option>
                                            <option value="D2" @if (old('prodi_jenjang') == 'D2') selected @endif>D2
                                            </option>
                                            <option value="D3" @if (old('prodi_jenjang') == 'D3') selected @endif>D3
                                            </option>
                                            <option value="D4" @if (old('prodi_jenjang') == 'D4') selected @endif>D4
                                            </option>
                                            <option value="S1" @if (old('prodi_jenjang') == 'S1') selected @endif>S1
                                            </option>
                                            <option value="S2" @if (old('prodi_jenjang') == 'S2') selected @endif>S2
                                            </option>
                                            <option value="S3" @if (old('prodi_jenjang') == 'S3') selected @endif>S3
                                            </option>
                                        </select>
                                        @if ($errors->has('prodi_jenjang'))
                                            <span class="text-danger">{{ $errors->first('prodi_jenjang') }}</span>
                                        @endif
                                        @error('prodi_jenjang')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-prodi_jenjang"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Surat Keputusan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_nomor" class="required-label">Nomor Surat Keputusan</label>
                                        <input type="text" name="sk_nomor" class="form-control"
                                            value="{{ old('sk_nomor') }}" required>
                                        @if ($errors->has('sk_nomor'))
                                            <span class="text-danger">{{ $errors->first('sk_nomor') }}</span>
                                        @endif
                                        @error('sk_nomor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_nomor"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal" class="form-control"
                                            value="{{ old('sk_tanggal') }}" required>
                                        @if ($errors->has('sk_tanggal'))
                                            <span class="text-danger">{{ $errors->first('sk_tanggal') }}</span>
                                        @endif
                                        @error('sk_tanggal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_tanggal"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="id_jenis_surat_keputusan" class="required-label">Jenis Surat
                                            Keputusan</label>
                                        <select name="id_jenis_surat_keputusan" class="form-control select-search">
                                            <option value="">-- Pilih Perguruan Tinggi --</option>
                                            @foreach ($jenis as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    @if (old('id_jenis_surat_keputusan') == $jenis->id) selected @endif>
                                                    {{ $jenis->jsk_nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('id_jenis_surat_keputusan'))
                                            <span
                                                class="text-danger">{{ $errors->first('id_jenis_surat_keputusan') }}</span>
                                        @endif
                                        @error('id_jenis_surat_keputusan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-id_jenis_surat_keputusan"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen">Dokumen SK (Opsional)</label>
                                        <input type="file" name="sk_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        @if ($errors->has('sk_dokumen'))
                                            <span class="text-danger">{{ $errors->first('sk_dokumen') }}</span>
                                        @endif
                                        @error('sk_dokumen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_dokumen"></small>
                                    </div>
                                </div>
                                <div class="btn-center mt-6">
                                    <div id="buttons">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="{{ route('perguruan-tinggi.index') }}" type="submit"
                                            class="btn btn-primary">Keluar</a>
                                    </div>
                                    {{-- <div id="loading">
                                        <div class="d-flex align-items-center">
                                            <strong>Loading...</strong>
                                            <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                                        </div>
                                    </div> --}}
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
            $('#formProdi').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('program-studi.validationStore') }}',
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
                    url: '{{ route('program-studi.store') }}',
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

