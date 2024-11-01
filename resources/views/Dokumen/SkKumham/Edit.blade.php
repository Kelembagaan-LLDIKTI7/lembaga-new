@extends('Layouts.Main')

@section('title', 'Edit SK Kumham')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('sk-kumham.update', $skKumham->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_akta" value="{{ $akta->id }}" class="form-control" required>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Update SK Kumham</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_nomor" class="required-label">No Akta</label>
                                        <input type="text" class="form-control" id="kumham_nomor" name="kumham_nomor"
                                            value="{{ $skKumham->kumham_nomor }}" required>
                                        @error('kumham_nomor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kumham_tanggal" class="required-label">Tanggal Akta</label>
                                        <input type="date" class="form-control" id="kumham_tanggal" name="kumham_tanggal"
                                            value="{{ $skKumham->kumham_tanggal }}" required>
                                        @error('kumham_tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kumham_perihal" class="required-label">Perihal</label>
                                        <input type="text" class="form-control" id="kumham_perihal" name="kumham_perihal"
                                            value="{{ $skKumham->kumham_perihal }}" required>
                                        @error('kumham_perihal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="kumham_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="kumham_dokumen" class="form-control"
                                            accept=".pdf,.doc,.docx" onchange="previewFile(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX.</small>
                                        <div id="file-preview">
                                            @if ($skKumham->kumham_dokumen)
                                                <p>File sebelumnya: <a
                                                        href="{{ asset('storage/' . $skKumham->kumham_dokumen) }}"
                                                        target="_blank">Lihat dokumen</a></p>
                                                @if (pathinfo($skKumham->kumham_dokumen, PATHINFO_EXTENSION) === 'pdf')
                                                    <iframe src="{{ asset('storage/' . $skKumham->kumham_dokumen) }}"
                                                        width="100%" height="400px"></iframe>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-center mt-3">
                                    <a href="{{ route('badan-penyelenggara.index') }}"
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
