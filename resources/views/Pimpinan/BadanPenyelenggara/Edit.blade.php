@extends('Layouts.Main')

@section('title', 'Edit Pimpinan Perguruan Tinggi')

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
                <form action="{{ route('pimpinan-badan-penyelenggara.update', $pimpinan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_organization" value="{{ $pimpinan->id_organization }}" class="form-control"
                        required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Edit Pimpinan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="pimpinan_nama" class="required-label">
                                            Nama Pimpinan
                                        </label>
                                        <input type="text" name="pimpinan_nama" class="form-control"
                                            value="{{ $pimpinan->pimpinan_nama }}" required>
                                        @error('pimpinan_nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_email" class="required-label">
                                            Email Pimpinan
                                        </label>
                                        <input type="email" name="pimpinan_email" class="form-control"
                                            value="{{ $pimpinan->pimpinan_email }}" required>
                                        @error('pimpinan_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_sk" class="required-label">
                                            No SK Pimpinan
                                        </label>
                                        <input type="text" name="pimpinan_sk" class="form-control"
                                            value="{{ $pimpinan->pimpinan_sk }}" required>
                                        @error('pimpinan_sk')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="pimpinan_tanggal" class="required-label">
                                            Tanggal Dilantik
                                        </label>
                                        <input type="date" name="pimpinan_tanggal" class="form-control"
                                            value="{{ $pimpinan->pimpinan_tanggal }}" required>
                                        @error('pimpinan_tanggal')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="pimpinan_status" class="required-label">Status Akreditasi</label>
                                        <input type="text" disabled value="{{ $pimpinan->pimpinan_status }}"
                                            class="form-control" name="pimpinan_status">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="id_jabatan" class="required-label">
                                            Jabatan Pimpinan
                                        </label>
                                        <select name="id_jabatan" class=" form-control select-search">
                                            <option value="">-- Pilih Peringkat --</option>
                                            @foreach ($jabatan as $jabatan)
                                                <option value="{{ $jabatan->id }}"
                                                    {{ $pimpinan->id_jabatan == $jabatan->id ? 'selected' : '' }}>
                                                    {{ $jabatan->jabatan_nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jabatan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="pimpinan_sk_dokumen" class="required-label">Dokumen SK</label>
                                    <input type="file" name="pimpinan_sk_dokumen" class="form-control"
                                        accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC, DOCX.</small>
                                    <div id="file-preview" class="mt-3"></div>
                                    @error('pimpinan_sk_dokumen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('badan-penyelenggara.show', ['id' => $pimpinan->id_organization]) }}"
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