@extends('Layouts.Main')

@section('title', 'Edit Perguruan-Tinggi')

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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('perguruan-tinggi.show', ['id' => $perguruanTinggi->id]) }}">
            <i class="fas fa-arrow-left mb-4 me-2"></i> Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <form id="formPTedit" action="{{ route('perguruan-tinggi.update', $perguruanTinggi->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Edit Perguruan Tinggi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_kode">Kode Perguruan Tinggi</label>
                                        <div class="organisasi-kode-input-group">
                                            <input type="text" class="form-control" id="organisasi_kode"
                                                name="organisasi_kode" value="{{ $perguruanTinggi->organisasi_kode }}"
                                                required>
                                        </div>
                                        @if ($errors->has('organisasi_kode'))
                                            <span class="text-danger">{{ $errors->first('organisasi_kode') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_kode"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan
                                            Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_nama }}" required>
                                        @if ($errors->has('organisasi_nama'))
                                            <span class="text-danger">{{ $errors->first('organisasi_nama') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_nama"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_nama_singkat }}">
                                        @if ($errors->has('organisasi_nama_singkat'))
                                            <span class="text-danger">{{ $errors->first('organisasi_nama_singkat') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_nama_singkat"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan
                                            Tinggi</label>
                                        <input type="text" name="organisasi_email" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_email }}" required>
                                        @if ($errors->has('organisasi_email'))
                                            <span class="text-danger">{{ $errors->first('organisasi_email') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_email"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}"
                                                    {{ $perguruanTinggi->organisasi_kota == $kota->nama ? 'selected' : '' }}>
                                                    {{ $kota->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('organisasi_kota'))
                                            <span class="text-danger">{{ $errors->first('organisasi_kota') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_kota"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_alamat }}" required>
                                        @if ($errors->has('organisasi_alamat'))
                                            <span class="text-danger">{{ $errors->first('organisasi_alamat') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_alamat"></small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="parent_id">Badan Penyelenggara</label>
                                        <select name="parent_id" class="form-control select-search" required>
                                            <option value="">-- Pilih Parent Organisasi --</option>
                                            @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                                <option value="{{ $badanPenyelenggara->id }}"
                                                    {{ $perguruanTinggi->parent_id == $badanPenyelenggara->id ? 'selected' : '' }}>
                                                    {{ $badanPenyelenggara->organisasi_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('parent_id'))
                                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-parent_id"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="bentuk_pt" class="required-label">Bentuk PT</label>
                                        <select name="organisasi_bentuk_pt" class="form-control select-search" required>
                                            <option value="">-- Pilih Bentuk PT --</option>
                                            @foreach ($bentukPt as $bentuk)
                                                <option value="{{ $bentuk->id }}"
                                                    {{ $perguruanTinggi->organisasi_bentuk_pt == $bentuk->id ? 'selected' : '' }}>
                                                    {{ $bentuk->bentuk_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('organisasi_bentuk_pt'))
                                            <span class="text-danger">{{ $errors->first('organisasi_bentuk_pt') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_bentuk_pt"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_telp }}" required>
                                        @if ($errors->has('organisasi_telp'))
                                            <span class="text-danger">{{ $errors->first('organisasi_telp') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_telp"></small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_website }}">
                                        @if ($errors->has('organisasi_website'))
                                            <span class="text-danger">{{ $errors->first('organisasi_website') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_website"></small>
                                    </div>

                                    @if ($changeStatus)
                                        <div class="form-group mb-3">
                                            <label for="status_pt" class="required-label">Bentuk PT</label>
                                            <select name="organisasi_status" class="form-control select-search" required>
                                                <option value="">-- Pilih Status PT --</option>
                                                <option value="Aktif"
                                                    {{ $perguruanTinggi->organisasi_status == 'Aktif' ? 'selected' : '' }}>
                                                    Aktif
                                                </option>
                                                <option value="Tutup"
                                                    {{ $perguruanTinggi->organisasi_status == 'Tutup' ? 'selected' : '' }}>
                                                    Tutup
                                                </option>
                                                <option value="Alih Bentuk"
                                                    {{ $perguruanTinggi->organisasi_status == 'Alih Bentuk' ? 'selected' : '' }}>
                                                    Alih Bentuk
                                                </option>
                                            </select>
                                            @if ($errors->has('organisasi_status'))
                                                <span class="text-danger">{{ $errors->first('organisasi_status') }}</span>
                                            @endif
                                            <small class="text-danger error-message" id="error-organisasi_status"></small>
                                        </div>
                                    @endif

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo">Logo Perguruan
                                            Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control"
                                            accept="image/png, image/jpg, image/jpeg, image/gif">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        @if ($errors->has('organisasi_logo'))
                                            <span class="text-danger">{{ $errors->first('organisasi_logo') }}</span>
                                        @endif
                                        <small class="text-danger error-message" id="error-organisasi_logo"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <div id="buttons">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
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
            $('#formPTedit').on('submit', function(event) {
                event.preventDefault(); // Menghentikan submit default form

                $('#buttons').hide();
                $('#loading').show();

                // Mengambil data form
                const formData = new FormData(this);

                // AJAX request ke server untuk validasi
                $.ajax({
                    url: '{{ route('perguruan-tinggi.validationUpdate', ['id' => $perguruanTinggi->id]) }}',
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
                    url: '{{ route('perguruan-tinggi.update', ['id' => $perguruanTinggi->id]) }}',
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
