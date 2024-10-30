@extends('Layouts.Main')

@section('title', 'Tambah Perguruan-Tinggi')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('perguruan-tinggi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Perguruan Tinggi</h5>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan Tinggi</label>
                                        <input type="email" name="organisasi_email" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <input type="text" name="organisasi_kota" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="parent_id">Badan Penyelenggara</label>
                                        <select name="parent_id" class="form-control select-search" required>
                                            <option value="">-- Pilih Parent Organisasi --</option>
                                            @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                                <option value="{{ $badanPenyelenggara->id }}">
                                                    {{ $badanPenyelenggara->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Perguruan Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control" required
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF.</small>
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_berubah_id">Tipe Perubahan</label>
                                        <select class="form-control select-search" id="changeType" required>
                                            <option value="pendirian">Pendirian</option>
                                            <option value="penyatuan">Penyatuan</option>
                                            <option value="penggabungan">Penggabungan</option>
                                        </select>
                                    </div>

                                    <div id="perguruan-tinggi-select" class="hidden">
                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_1">Perguruan Tinggi 1</label>
                                            <select name="perguruan_tinggi_1" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggi)
                                                    <option value="{{ $perguruanTinggi->id }}">
                                                        {{ $perguruanTinggi->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_2">Perguruan Tinggi 2</label>
                                            <select name="perguruan_tinggi_2" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggi)
                                                    <option value="{{ $perguruanTinggi->id }}">
                                                        {{ $perguruanTinggi->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="additional-perguruan-tinggi" class="hidden">
                                        <button type="button" id="addPerguruanTinggi" class="btn btn-secondary">Tambah
                                            Perguruan Tinggi</button>
                                    </div>

                                    <div id="dynamic-perguruan-tinggi"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>

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
                                <label for="perguruan_tinggi">Perguruan Tinggi Tambahan</label>
                                <select name="perguruan_tinggi_tambahan[]" class="form-control select-search">
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
