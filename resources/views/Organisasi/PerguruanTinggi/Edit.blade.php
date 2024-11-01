@extends('Layouts.Main')

@section('title', 'Edit Perguruan-Tinggi')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('perguruan-tinggi.update', $perguruanTinggi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Perguruan Tinggi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control" value="{{ $perguruanTinggi->organisasi_nama }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control" value="{{ $perguruanTinggi->organisasi_nama_singkat }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan Tinggi</label>
                                        <input type="email" name="organisasi_email" class="form-control" value="{{ $perguruanTinggi->organisasi_email }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control" value="{{ $perguruanTinggi->organisasi_telp }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}" {{ $perguruanTinggi->organisasi_kota == $kota->nama ? 'selected' : '' }}>
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control" value="{{ $perguruanTinggi->organisasi_alamat }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control" value="{{ $perguruanTinggi->organisasi_website }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="parent_id">Badan Penyelenggara</label>
                                        <select name="parent_id" class="form-control select-search" required>
                                            <option value="">-- Pilih Parent Organisasi --</option>
                                            @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                                <option value="{{ $badanPenyelenggara->id }}" {{ $perguruanTinggi->parent_id == $badanPenyelenggara->id ? 'selected' : '' }}>
                                                    {{ $badanPenyelenggara->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Perguruan Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control"
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG, GIF.</small>
                                        <img id="logo-preview" src="{{ asset('storage/' . $perguruanTinggi->organisasi_logo) }}" alt="Preview Logo" style="display: block;">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_berubah_id">Tipe Perubahan</label>
                                        <select class="form-control select-search" name="perubahan" id="changeType" required>
                                            <option value="Aktif" {{ $perguruanTinggi->perubahan == 'Aktif' ? 'selected' : '' }}>Pendirian</option>
                                            <option value="penyatuan" {{ $perguruanTinggi->perubahan == 'penyatuan' ? 'selected' : '' }}>Penyatuan</option>
                                            <option value="penggabungan" {{ $perguruanTinggi->perubahan == 'penggabungan' ? 'selected' : '' }}>Penggabungan</option>
                                        </select>
                                    </div>

                                    <div id="perguruan-tinggi-select" class="{{ $perguruanTinggi->perubahan == 'penyatuan' || $perguruanTinggi->perubahan == 'penggabungan' ? '' : 'hidden' }}">
                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_1">Perguruan Tinggi 1</label>
                                            <select name="perguruan_tinggi_1" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggiOption)
                                                    <option value="{{ $perguruanTinggiOption->id }}" {{ $perguruanTinggi->perguruan_tinggi_1 == $perguruanTinggiOption->id ? 'selected' : '' }}>
                                                        {{ $perguruanTinggiOption->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_2">Perguruan Tinggi 2</label>
                                            <select name="perguruan_tinggi_2" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggiOption)
                                                    <option value="{{ $perguruanTinggiOption->id }}" {{ $perguruanTinggi->perguruan_tinggi_2 == $perguruanTinggiOption->id ? 'selected' : '' }}>
                                                        {{ $perguruanTinggiOption->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="additional-perguruan-tinggi" class="{{ $perguruanTinggi->perubahan == 'penggabungan' ? '' : 'hidden' }}">
                                        <button type="button" id="addPerguruanTinggi" class="btn btn-secondary">
                                            Edit Perguruan Tinggi
                                        </button>
                                    </div>

                                    <div id="dynamic-perguruan-tinggi"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="{{ route('perguruan-tinggi.index') }}" class="btn btn-primary">Keluar</a>
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
            $('#changeType').change(function() {
                var selected = $(this).val();
                if (selected === 'penyatuan' || selected === 'penggabungan') {
                    $('#perguruan-tinggi-select').removeClass('hidden');
                    if (selected === 'penggabungan') {
                        $('#additional-perguruan-tinggi').removeClass('hidden');
                    } else {
                        $('#additional-perguruan-tinggi').addClass('hidden');
                    }
                } else {
                    $('#perguruan-tinggi-select').addClass('hidden');
                    $('#additional-perguruan-tinggi').addClass('hidden');
                }
            });

            $('#addPerguruanTinggi').click(function() {
                var html = `<div class="form-group mb-3">
                                <label for="perguruan_tinggi">Perguruan Tinggi Editan</label>
                                <select name="perguruan_tinggi_Editan[]" class="form-control select-search">
                                    <option value="">-- Pilih Perguruan Tinggi --</option>
                                    @foreach ($perguruanTinggis as $perguruanTinggi)
                                        <option value="{{ $perguruanTinggi->id }}">{{ $perguruanTinggi->organisasi_nama }}</option>
                                    @endforeach
                                </select>
                            </div>`;
                $('#dynamic-perguruan-tinggi').append(html);
                $('.select-search').select2();
            });
        });

        function previewLogo(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('logo-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
