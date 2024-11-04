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
    </style>
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
                                <div class="col-md-4">
                                                <label for="validationCustom01" class="form-label">Kode Perguruan Tinggi</label>
                                                <div class="organisasi-kode-input-group" id="organisasi-kode-input-group">
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_1" required>
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_2" required>
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_3" required>
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_4" required>
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_5" required>
                                                    <input type="text" maxlength="1" class="form-control organisasi-kode"
                                                        id="organisasi_kode_6" required>
                                                    <input type="hidden" id="organisasi_kode" name="organisasi_kode" value="">
                                                </div>
                                            </div>
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
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}">
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
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
                                        <select class="form-control select-search" name="perubahan" id="changeType"
                                            required>
                                            <option value="Aktif">Pendirian</option>
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
                                        <button type="button" id="addPerguruanTinggi" class="btn btn-secondary">
                                            Tambah Perguruan Tinggi
                                        </button>
                                    </div>

                                    <div id="dynamic-perguruan-tinggi"></div>
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
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal" class="form-control" required>
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
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="sk_dokumen" class="form-control" required
                                            accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('perguruan-tinggi.index') }}" type="submit"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInputs = document.querySelectorAll('.organisasi-kode');
            const kodeHiddenInput = document.getElementById('organisasi_kode');

            kodeInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    // Move to the next input field if there's input and it's not the last field
                    if (input.value.length === 1 && index < kodeInputs.length - 1) {
                        kodeInputs[index + 1].focus();
                    }

                    // Update the hidden input field
                    let kodeValue = '';
                    kodeInputs.forEach(kodeInput => {
                        kodeValue += kodeInput.value;
                    });
                    kodeHiddenInput.value = kodeValue;

                    // Ensure the value is 6 characters
                    if (kodeValue.length === 6) {
                        kodeHiddenInput.value = kodeValue;
                    }
                });

                input.addEventListener('keydown', function(e) {
                    // Handle backspace to move focus back to the previous input
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        kodeInputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
@endsection
