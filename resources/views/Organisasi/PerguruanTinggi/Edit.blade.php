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
        <div class="row">
            <div class="col-12">
                <form action="{{ route('perguruan-tinggi.update', $perguruanTinggi->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Perguruan Tinggi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_kode" class="form-label">Kode Perguruan Tinggi</label>
                                        <div class="organisasi-kode-input-group">
                                            <input type="text" class="form-control" id="organisasi_kode"
                                                name="organisasi_kode" value="{{ $perguruanTinggi->organisasi_kode }}"
                                                required>
                                        </div>
                                        @if ($errors->has('organisasi_kode'))
                                            <span class="text-danger">{{ $errors->first('organisasi_kode') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_nama }}" required>
                                        @if ($errors->has('organisasi_nama'))
                                            <span class="text-danger">{{ $errors->first('organisasi_nama') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_nama_singkat }}">
                                        @if ($errors->has('organisasi_nama_singkat'))
                                            <span class="text-danger">{{ $errors->first('organisasi_nama_singkat') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan Tinggi</label>
                                        <input type="email" name="organisasi_email" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_email }}" required>
                                        @if ($errors->has('organisasi_email'))
                                            <span class="text-danger">{{ $errors->first('organisasi_email') }}</span>
                                        @endif
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
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_alamat }}" required>
                                        @if ($errors->has('organisasi_alamat'))
                                            <span class="text-danger">{{ $errors->first('organisasi_alamat') }}</span>
                                        @endif
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
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_telp }}" required>
                                        @if ($errors->has('organisasi_telp'))
                                            <span class="text-danger">{{ $errors->first('organisasi_telp') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control"
                                            value="{{ $perguruanTinggi->organisasi_website }}">
                                        @if ($errors->has('organisasi_website'))
                                            <span class="text-danger">{{ $errors->first('organisasi_website') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Perguruan
                                            Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control"
                                            accept="image/png, image/jpg, image/jpeg, image/gif">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        @if ($errors->has('organisasi_logo'))
                                            <span class="text-danger">{{ $errors->first('organisasi_logo') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="{{ route('perguruan-tinggi.show', ['id' => $perguruanTinggi->id]) }}"
                                        class="btn btn-primary">Keluar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInputs = document.querySelectorAll('.organisasi-kode');
            const kodeHiddenInput = document.getElementById('organisasi_kode');

            kodeInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (input.value.length === 1 && index < kodeInputs.length - 1) {
                        kodeInputs[index + 1].focus();
                    }

                    let kodeValue = '';
                    kodeInputs.forEach(kodeInput => {
                        kodeValue += kodeInput.value;
                    });
                    kodeHiddenInput.value = kodeValue;

                    if (kodeValue.length === 6) {
                        kodeHiddenInput.value = kodeValue;
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        kodeInputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
@endsection
