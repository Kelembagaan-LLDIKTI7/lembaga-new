@extends('Layouts.Main')

@section('title', 'Tambah Perguruan-Tinggi')

@section('css')
    <style>
        .organisasi-kode-input-group {
            display: flex;
            gap: 5px;
        }

        .organisasi-kode {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 0;
        }

        input:focus {
            border-color: #66afe9;
            outline: none;
        }

        .select-search option:checked {
            background-color: blue !important;
            color: white !important;
        }

        .select-search {
            --bs-select-item-active-bg: blue;
        }

        .select2-selection__choice {
            background-color: rgb(3, 163, 236) !important;
            color: white !important;
            border-color: rgb(3, 163, 236) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formPT" action="{{ route('perguruan-tinggi.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Perguruan Tinggi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-4">
                                        <label for="validationCustom01" class="required-label">Kode Perguruan Tinggi</label>
                                        <div class="organisasi-kode-input-group" id="organisasi-kode-input-group">
                                            <input type="text" class="form-control" id="organisasi_kode"
                                                name="organisasi_kode" required>
                                            @error('organisasi_kode')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <small class="text-danger error-message" id="error-organisasi_kode"></small>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control" required>
                                        @error('organisasi_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_nama"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control">
                                        @error('organisasi_nama_singkat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_nama_singkat"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan Tinggi</label>
                                        <input type="email" name="organisasi_email" class="form-control" required>
                                        @error('organisasi_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_email"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control" required>
                                        @error('organisasi_telp')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_telp"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}">
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('organisasi_kota')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_kota"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control" required>
                                        @error('organisasi_alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_alamat"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control">
                                        @error('organisasi_website')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_website"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="parent_id">Badan Penyelenggara</label>
                                        <select name="parent_id" class="form-control select-search">
                                            <option value="">-- Pilih Parent Organisasi --</option>
                                            @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                                <option value="{{ $badanPenyelenggara->id }}">
                                                    {{ $badanPenyelenggara->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-parent_id"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_bentuk_pt" class="required-label">Bentuk Perguruan
                                            Tinggi</label>
                                        <select name="organisasi_bentuk_pt" class="form-control select-search" required>
                                            <option value="">-- Pilih Bentuk PT --</option>
                                            @foreach ($bentukPt as $pt)
                                                <option value="{{ $pt->id }}">
                                                    {{ $pt->bentuk_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('organisasi_bentuk_pt')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_bentuk_pt"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo">Logo Perguruan Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control"
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                        @error('organisasi_logo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_logo"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="berubah">Tipe Perubahan</label>
                                        <select class="form-control select-search" name="berubah" id="berubah"
                                            required>
                                            <option value="Aktif">Pendirian</option>
                                            <option value="Alih Bentuk">Alih Bentuk</option>
                                            <option value="Penggabungan">Penggabungan</option>
                                        </select>
                                        @error('berubah')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-berubah"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_berubah_id">PT Yang diubah</label>
                                        <select class="form-control select-search" name="organisasi_berubah_id[]"
                                            id="organisasi_berubah_id" multiple>
                                            <option value="">-- Pilih Perguruan Tinggi --</option>
                                            @foreach ($perguruanTinggis as $perguruanTinggi)
                                                <option value="{{ $perguruanTinggi->id }}">
                                                    {{ $perguruanTinggi->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('organisasi_berubah_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-organisasi_berubah_id"></small>
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
                                        <input type="text" name="sk_nomor" class="form-control" required>
                                        @error('sk_nomor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_nomor"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal" class="form-control" required>
                                        @error('sk_tanggal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_tanggal"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="id_jenis_surat_keputusan">Jenis Surat Keputusan</label>
                                        <select name="id_jenis_surat_keputusan" class="form-control select-search">
                                            <option value="">-- Pilih Perguruan Tinggi --</option>
                                            @foreach ($jenis as $jenis)
                                                <option value="{{ $jenis->id }}">
                                                    {{ $jenis->jsk_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jenis_surat_keputusan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message"
                                            id="error-id_jenis_surat_keputusan"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="sk_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        @error('sk_dokumen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-danger error-message" id="error-sk_dokumen"></small>
                                    </div>
                                </div>
                                <div id="buttons" class="mb-2 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('perguruan-tinggi.index') }}" type="submit"
                                        class="btn btn-secondary">Keluar</a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                <div id="loading">
                                    <button type="button" class="btn btn-primary" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                                <div id="error-messages"></div>
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
            $('#formPT').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('perguruan-tinggi.validationStore') }}',
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
                    url: '{{ route('perguruan-tinggi.store') }}',
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
@endsection
