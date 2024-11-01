@extends('Layouts.Main')

@section('title', 'Tambah Akreditasi Perguruan Tinggi')

@section('css')
    <style>
        .btn-center {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-sm-custom {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('akreditasi-perguruan-tinggi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_organization" value="{{ $pt->id }}" class="form-control" required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Tambah Akreditasi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akreditasi_sk" class="required-label">Nomor Surat Keputusan
                                            Akreditasi</label>
                                        <input type="text" name="akreditasi_sk" class="form-control" required>
                                        @error('akreditasi_sk')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="akreditasi_tgl_awal" class="required-label">Tanggal Mulai
                                            Berlaku</label>
                                        <input type="date" name="akreditasi_tgl_awal" class="form-control" required>
                                        @error('akreditasi_tgl_awal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_peringkat_akreditasi" class="required-label">Peringkat
                                            Akreditasi</label>
                                        <select name="id_peringkat_akreditasi" class="form-control select-search">
                                            <option value="">-- Pilih Peringkat --</option>
                                            @foreach ($peringkat as $peringkat)
                                                <option value="{{ $peringkat->id }}">{{ $peringkat->peringkat_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_peringkat_akreditasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="akreditasi_status" class="required-label">Status Akreditasi</label>
                                        <select name="akreditasi_status" class="form-control select-search" required>
                                            <option value="">-- Pilih Peringkat --</option>
                                            <option value="Berlaku">Berlaku</option>
                                            <option value="Dicabut">Dicabut</option>
                                            <option value="Tidak Berlaku">Tidak Berlaku</option>
                                        </select>
                                        @error('akreditasi_status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="akreditasi_tgl_akhir" class="required-label">Tanggal Akhir
                                            Berlaku</label>
                                        <input type="date" name="akreditasi_tgl_akhir" class="form-control" required>
                                        @error('akreditasi_tgl_akhir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_lembaga_akreditasi" class="required-label">Peringkat
                                            Akreditasi</label>
                                        <select name="id_lembaga_akreditasi" class="form-control select-search">
                                            <option value="">-- Pilih Peringkat --</option>
                                            @foreach ($lembaga as $lembaga)
                                                <option value="{{ $lembaga->id }}">{{ $lembaga->lembaga_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_lembaga_akreditasi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                    <input type="file" name="sk_dokumen" class="form-control" required
                                        accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC, DOCX.</small>
                                    <div id="file-preview" class="mt-3"></div> <!-- Tempat untuk preview -->
                                    @error('sk_dokumen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('perguruan-tinggi.index') }}"
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
