@extends('Layouts.Main')

@section('title', 'Tambah SK Perguruan Tinggi')

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
                <form action="{{ route('sk-perguruan-tinggi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_organization" value="{{ $id_organization }}" class="form-control" required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Tambah SK</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_nomor" class="required-label">
                                            No SK
                                        </label>
                                        <input type="text" name="sk_nomor" class="form-control" required>
                                        @error('sk_nomor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">
                                            Tanggal Terbit SK
                                        </label>
                                        <input type="date" name="sk_tanggal" class="form-control" required>
                                        @error('sk_tanggal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="id_jenis_surat_keputusan" class="required-label">
                                            Jenis SK
                                        </label>
                                        <select name="id_jenis_surat_keputusan" class="form-control select-search">
                                            <option value="">-- Pilih Jenis --</option>
                                            @foreach ($jenis as $j)
                                                <option value="{{ $j->id }}">{{ $j->jsk_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jenis_surat_keputusan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="sk_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        @error('sk_dokumen')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div id="file-preview" class="mt-3"></div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('perguruan-tinggi.show', ['id' => $id_organization]) }}"
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
