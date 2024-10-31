@extends('Layouts.Main')
@section('title', 'Tambah Pimpinan')
@section('css')
    <link rel="stylesheet" href="{{ asset('dist/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('pimpinan-organisasi.store') }}" method="POST" class="row g-3"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label for="validationCustom01" class="form-label">Nama Pimpinan</label>
                        <input type="text" class="form-control" placeholder="Nama" id="validationCustom01"
                            name="pimpinan_nama" value="" required>
                        @error('pimpinan_nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom02" class="form-label">Tanggal Dilantik</label>
                        <input type="date" class="form-control" id="validationCustom02" name="pimpinan_tanggal"
                            value="" placeholder="Tanggal" required>
                        @error('pimpinan_tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom03" class="form-label">Email Pimpinan</label>
                        <input type="email" class="form-control" placeholder="Email" id="validationCustom03"
                            name="pimpinan_email" value="" required>
                        @error('pimpinan_email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jabatanLabel" class="form-label">Jabatan Pimpinan</label>
                        <select class="form-control" id="kotaAkta" data-choices name="id_jabatan" required>
                            <option value="">Jabatan</option>
                            @foreach ($jabatan as $j)
                                <option value="{{ $j->id }}">{{ $j->jabatan_nama }}</option>
                            @endforeach
                        </select>
                        @error('id_jabatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom04" class="form-label">No SK Pimpinan</label>
                        <input type="text" class="form-control" placeholder="Nomor SK" id="validationCustom04"
                            name="pimpinan_sk" value="" required>
                        @error('pimpinan_sk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom04" class="form-label">Status Pimpinan</label>
                        <input type="text" class="form-control" id="validationCustom04" name="pimpinan_status"
                            value="Aktif" required readonly>
                        @error('pimpinan_status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Dokumen SK Pimpinan<span style="color: red">*</span>
                                    <small>(pdf)</small>
                                </h5>
                                <div class="mb-3">
                                    <iframe id="sk_preview" height="175" width="100%" frameborder="0"></iframe>
                                </div>
                                <div class="mt-3">
                                    <input type="file" id="skFileInp" name="pimpinan_sk_dokumen" class="d-none"
                                        accept=".pdf">
                                    <label for="skFileInp" class="btn btn-primary">
                                        <i class="ri-upload-2-line me-1"></i> Choose File
                                    </label>
                                    <p id="selectedFileName" class="mt-2">No file chosen</p>
                                </div>
                            </div>
                            @error('pimpinan_sk_dokumen')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <input type="hidden" name="id_organization" value="{{ $id }}">

                    <div class="col-12 mb-4">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('skFileInp').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            document.getElementById('selectedFileName').textContent = fileName;

            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('sk_preview').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
@endsection
