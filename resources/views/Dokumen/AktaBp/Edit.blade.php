@extends('Layouts.Main')

@section('title', 'Edit Akta')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('akta-badan-penyelenggara.update', ['id' => $akta->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_organization" value="{{ $akta->id_organization }}" class="form-control"
                        required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Edit Akta</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01" class="required-label">No Akta</label>
                                        <input type="text" class="form-control" id="validationCustom01" name="akta_nomor"
                                            value="{{ old('akta_nomor', $akta->akta_nomor) }}" required>
                                        @error('akta_nomor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02" class="required-label">Tanggal Akta</label>
                                        <input type="date" class="form-control" id="validationCustom02"
                                            name="akta_tanggal" value="{{ old('akta_tanggal', $akta->akta_tanggal) }}"
                                            required>
                                        @error('akta_tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="validationCustom03" class="required-label">Nama Notaris</label>
                                        <input type="text" class="form-control" id="validationCustom03"
                                            name="akta_nama_notaris"
                                            value="{{ old('akta_nama_notaris', $akta->akta_nama_notaris) }}" required>
                                        @error('akta_nama_notaris')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="aktaJenis" class="required-label">Jenis Akta</label>
                                        <select class="form-control select-search" id="aktaJenis" name="akta_jenis"
                                            required>
                                            <option value="">Jenis</option>
                                            <option value="Pendirian"
                                                {{ old('akta_jenis', $akta->akta_jenis) == 'Pendirian' ? 'selected' : '' }}>
                                                Pendirian</option>
                                            <option value="Perubahan"
                                                {{ old('akta_jenis', $akta->akta_jenis) == 'Perubahan' ? 'selected' : '' }}>
                                                Perubahan</option>
                                        </select>
                                        @error('akta_jenis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kotaAkta" class="required-label">Kota Notaris</label>
                                        <select name="kotaAkta" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kota as $k)
                                                <option value="{{ $k->nama }}"
                                                    {{ $akta->akta_kota_notaris == $k->nama ? 'selected' : '' }}>
                                                    {{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="referensiAkta" class="required-label">Referensi Akta</label>
                                        <select class="form-control" id="referensiAkta" name="akta_referensi" disabled>
                                            <option value="">-- Pilih Referensi Akta --</option>
                                            @foreach ($aktas as $a)
                                                <option value="{{ $a->id }}"
                                                    {{ $a->id == old('akta_referensi', $akta->akta_referensi) ? 'selected' : '' }}>
                                                    {{ $a->akta_nomor }}</option>
                                            @endforeach
                                        </select>
                                        @error('akta_referensi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="aktaDokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="aktaDokumen" class="form-control"
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        <div id="file-preview"></div>
                                    </div>
                                </div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('badan-penyelenggara.show', ['id' => $akta->id_organization]) }}"
                                        class="btn btn-primary btn-sm-custom">Keluar</a>
                                    <button type="submit" class="btn btn-primary btn-sm-custom">Simpan</button>
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
            const aktaJenisSelect = document.getElementById('aktaJenis');
            const referensiAktaSelect = document.getElementById('referensiAkta');

            aktaJenisSelect.addEventListener('change', function() {
                if (aktaJenisSelect.value === 'Perubahan') {
                    referensiAktaSelect.disabled = false;
                } else {
                    referensiAktaSelect.disabled = true;
                    referensiAktaSelect.value = '';
                }
            });
        });

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