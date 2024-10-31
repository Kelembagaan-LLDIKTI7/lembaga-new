@extends('Layouts.Main')
@section('title', 'Tambah Akreditasi')
@section('css')
    <style>
        .file-input-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .label-input-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <form action="{{ route('akreditasi.store') }}" method="POST" class="row g-3" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">No SK Akreditasi</label>
            <input type="text" class="form-control" id="validationCustom01" name="akreditasi_sk" value="" required>
            @error('akreditasi_sk')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">Status Akreditasi</label>
            <select class="form-control" id="aktaJenis" data-choices name="akreditasi_status" required>
                <option value="">Status</option>
                <option value="Berlaku">Berlaku</option>
                <option value="Tidak Berlaku">Tidak Berlaku</option>
                <option value="Dicabut">Dicabut</option>
            </select>
            @error('akreditasi_status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Tanggal Mulai Berlaku</label>
            <input type="text" class="form-control" id="validationCustom02" name="akreditasi_tgl_awal" value=""
                data-provider="flatpickr" data-date-format="d M, Y" required>
            @error('akreditasi_tgl_awal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Tanggal Akhir Berlaku</label>
            <input type="text" class="form-control" id="validationCustom02" name="akreditasi_tgl_akhir" value=""
                data-provider="flatpickr" data-date-format="d M, Y" required>
            @error('akreditasi_tgl_akhir')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="validationCustom03" class="form-label">Lembaga Akreditasi</label>
            <select class="form-control" id="kotaAkta" data-choices name="id_lembaga_akreditasi" required>
                <option value="">Lembaga</option>
                @foreach ($lembaga as $l)
                    <option value="{{ $l->id }}">{{ $l->lembaga_nama }}</option>
                @endforeach
            </select>
            @error('id_lembaga_akreditasi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="kotaAktaLabel" class="form-label">Peringkat Akreditasi</label>
            <select class="form-control" id="kotaAkta" data-choices name="id_peringkat_akreditasi" required>
                <option value="">Peringkat</option>
                @foreach ($peringkat as $p)
                    <option value="{{ $p->id }}">{{ $p->peringkat_nama }}</option>
                @endforeach
            </select>
            @error('id_peringkat_akreditasi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">File Akreditasi<span style="color: red">*</span> <small>(pdf)</small>
                    </h5>
                    <div class="mb-3">
                        <iframe id="akreditasi_preview" height="175" width="100%" frameborder="0"></iframe>
                    </div>
                    <div class="mt-3">
                        <input type="file" id="akreditasiFileInp" name="akreditasi_dokumen" class="d-none"
                            accept=".pdf">
                        <label for="akreditasiFileInp" class="btn btn-primary">
                            <i class="ri-upload-2-line me-1"></i> Choose File
                        </label>
                        <p id="selectedFileName" class="mt-2">No file chosen</p>
                    </div>
                </div>
                @error('akta_dokumen')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- @include('layouts.agreement') --}}

        {{-- <input type="hidden" name="akta_referensi" value="{{ $akta_referensi }}"> --}}

        <input type="hidden" name="id_organization" value="{{ $id_organization }}">
        <input type="hidden" name="id_prodi" value="{{ $id_prodi }}">

        <div class="col-12 mb-4">
            <button class="btn btn-primary" type="submit">Submit form</button>
        </div>
    </form>
@endsection

@section('js')
    <script>
        document.getElementById('akreditasiFileInp').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            document.getElementById('selectedFileName').textContent = fileName;

            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('akreditasi_preview').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
@endsection
