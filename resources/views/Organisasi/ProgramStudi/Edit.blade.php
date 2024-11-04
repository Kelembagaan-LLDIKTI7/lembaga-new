@extends('Layouts.Main')

@section('title', 'Edit Program Studi')

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
                <form action="{{ route('program-studi.update', $prodi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Program Studi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                <div class="col-md-4">
                                    <label for="validationCustom01" class="form-label">Kode Program Studi</label>
                                    <div class="prodi-kode-input-group" id="prodi-kode-input-group">
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" maxlength="1" class="form-control prodi-kode"
                                                id="prodi_kode_{{ $i + 1 }}"
                                                value="{{ $prodi->prodi_kode[$i] ?? '' }}" required>
                                        @endfor
                                        <input type="hidden" id="prodi_kode" name="prodi_kode" value="{{ $prodi->prodi_kode }}">
                                    </div>
                                </div>
                                    <div class="form-group mb-3">
                                        <label for="prodi_nama" class="required-label">Nama Program Studi</label>
                                        <input type="text" value="{{ $prodi->prodi_nama }}" name="prodi_nama"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="prodi_active_status" class="required-label">Status Program</label>
                                        <select name="prodi_active_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Aktif"
                                                {{ $prodi->prodi_active_status == 'Aktif' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="Tidak Aktif"
                                                {{ $prodi->prodi_active_status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="prodi_jenjang" class="required-label">Jenjang Program</label>
                                        <select name="prodi_jenjang" class="form-control select-search" required>
                                            <option value="">-- Pilih Jenjang --</option>
                                            <option value="D1" {{ $prodi->prodi_jenjang == 'D1' ? 'selected' : '' }}>D1
                                            </option>
                                            <option value="D2" {{ $prodi->prodi_jenjang == 'D2' ? 'selected' : '' }}>D2
                                            </option>
                                            <option value="D3" {{ $prodi->prodi_jenjang == 'D3' ? 'selected' : '' }}>D3
                                            </option>
                                            <option value="D4" {{ $prodi->prodi_jenjang == 'D4' ? 'selected' : '' }}>D4
                                            </option>
                                            <option value="S1" {{ $prodi->prodi_jenjang == 'S1' ? 'selected' : '' }}>S1
                                            </option>
                                            <option value="S2" {{ $prodi->prodi_jenjang == 'S2' ? 'selected' : '' }}>S2
                                            </option>
                                            <option value="S3" {{ $prodi->prodi_jenjang == 'S3' ? 'selected' : '' }}>S3
                                            </option>
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
                                        <input type="text" name="sk_nomor"
                                            value="{{ $prodi->suratKeputusan->first()->sk_nomor ?? '' }}"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal"
                                            value="{{ $prodi->suratKeputusan->first()->sk_tanggal ?? '' }}"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="id_jenis_surat_keputusan">Jenis Surat Keputusan</label>
                                        <select name="id_jenis_surat_keputusan" class="form-control select-search">
                                            <option value="">-- Pilih Jenis Surat Keputusan --</option>
                                            @foreach ($jenis as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    {{ $prodi->suratKeputusan->first()->id_jenis_surat_keputusan == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->jsk_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($prodi->suratKeputusan->first() && $prodi->suratKeputusan->first()->sk_dokumen)
                                        <div class="form-group mb-3">
                                            <label>Dokumen SK Terbaru</label>
                                            <a href="{{ asset('storage/' . $prodi->suratKeputusan->first()->sk_dokumen) }}"
                                                target="_blank" class="form-control">
                                                Lihat Dokumen
                                            </a>
                                        </div>
                                    @endif

                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen" class="required-label">Unggah Dokumen Baru</label>
                                        <input type="file" name="sk_dokumen" id="sk_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        <div id="file-preview"></div>
                                    </div>
                                    @if ($prodi->suratKeputusan->first() && $prodi->suratKeputusan->first()->sk_dokumen)
                                        <input type="hidden" name="existing_sk_dokumen"
                                            value="{{ $prodi->suratKeputusan->first()->sk_dokumen }}">
                                    @endif

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('program-studi.show', ['id' => $prodi->id]) }}"
                                        class="btn btn-secondary">Keluar</a>
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
        document.getElementById('sk_dokumen').addEventListener('change', function(e) {
            var fileInput = e.target;
            var preview = document.getElementById('file-preview');
            var file = fileInput.files[0];

            preview.innerHTML = '';

            if (file) {
                var fileType = file.type;

                if (fileType === 'application/pdf') {
                    var objectUrl = URL.createObjectURL(file);
                    var previewElement = document.createElement('iframe');
                    previewElement.src = objectUrl;
                    previewElement.width = '100%';
                    previewElement.height = '500px';
                    preview.appendChild(previewElement);
                } else if (fileType === 'application/msword' || fileType ===
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                    var previewElement = document.createElement('p');
                    previewElement.textContent = 'File yang diunggah: ' + file.name;
                    preview.appendChild(previewElement);
                } else {
                    var errorElement = document.createElement('p');
                    errorElement.textContent = 'Format file tidak didukung untuk preview.';
                    preview.appendChild(errorElement);
                }
            }
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kodeInputs = document.querySelectorAll('.prodi-kode');
        const kodeHiddenInput = document.getElementById('prodi_kode');

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
