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
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="prodi_nama" class="required-label">Nama Program Studi</label>
                                        <input type="text" name="prodi_nama" class="form-control"
                                            value="{{ old('prodi_nama') }}" required>
                                        @if ($errors->has('prodi_nama'))
                                            <span class="text-danger">{{ $errors->first('prodi_nama') }}</span>
                                        @endif
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
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal" class="form-control"
                                            value="{{ old('sk_tanggal') }}" required>
                                        @if ($errors->has('sk_tanggal'))
                                            <span class="text-danger">{{ $errors->first('sk_tanggal') }}</span>
                                        @endif
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
