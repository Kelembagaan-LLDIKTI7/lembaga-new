@extends('Layouts.Main')

@section('title', 'Tambah Program Studi')

@section('css')
<style>
        .prodi-kode-input-group {
            display: flex;
            gap: 5px;
        }

        .prodi-kode {
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
                <form action="{{ route('program-studi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Program Studi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="id_organization" value="{{ $organisasi->id }}">
                                    <div class="col-md-4">
                                                <label for="validationCustom01" class="form-label">Kode Program Studi</label>
                                                <div class="prodi-kode-input-group" id="prodi-kode-input-group">
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_1" required>
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_2" required>
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_3" required>
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_4" required>
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_5" required>
                                                    <input type="text" maxlength="1" class="form-control prodi-kode"
                                                        id="prodi_kode_6" required>
                                                    <input type="hidden" id="prodi_kode" name="prodi_kode" value="">
                                                </div>
                                            </div>
                                    <div class="form-group mb-3">
                                        <label for="prodi_nama" class="required-label">Nama Program Studi</label>
                                        <input type="text" name="prodi_nama" class="form-control" required>
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="prodi_active_status" class="required-label">Status</label>
                                        <select name="prodi_active_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="prodi_jenjang" class="required-label">Jenjang</label>
                                        <select name="prodi_jenjang" class="form-control select-search" required>
                                            <option value="">-- Pilih Jenjang --</option>
                                            <option value="D1">D1</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="D4">D4</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
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
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInputs = document.querySelectorAll('.prodi-kode');
            const kodeHiddenInput = document.getElementById('prodi_kode');

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
