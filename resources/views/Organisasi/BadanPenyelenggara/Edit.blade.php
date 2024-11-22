@extends('Layouts.Main')

@section('title', 'Tambah Badan Penyelenggara')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="formBP" action="{{ route('badan-penyelenggara.update', ['id' => $bp->id]) }}" method="POST"
                    enctype="multipart/form-data" onsubmit="return validateForm()">
                    @csrf
                    @method('PUT')
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Edit Badan Penyelenggara</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Badan Penyelenggara</label>
                                        <input type="text" name="organisasi_nama" class="form-control"
                                            value="{{ $bp->organisasi_nama }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control"
                                            value="{{ $bp->organisasi_nama_singkat }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="label">Email Badan
                                            Penyelenggara</label>
                                        <input type="email" name="organisasi_email" class="form-control"
                                            value="{{ $bp->organisasi_email }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control"
                                            value="{{ $bp->organisasi_alamat }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control"
                                            value="{{ $bp->organisasi_website }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control"
                                            value="{{ $bp->organisasi_telp }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search">
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}"
                                                    {{ $bp->organisasi_kota == $kota->nama ? 'selected' : '' }}>
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('badan-penyelenggara.show', ['id' => $bp->id]) }}"
                        class="btn btn-secondary">Keluar</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
    </script>
@endsection
