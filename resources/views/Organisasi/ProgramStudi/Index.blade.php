@extends('Layouts.Main')

@section('title', 'Tambah Perguruan-Tinggi')

@section('css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('perguruan-tinggi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Perguruan Tinggi</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama" class="required-label">Nama Perguruan Tinggi</label>
                                        <input type="text" name="organisasi_nama" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_nama_singkat" class="required-label">Nama Singkat</label>
                                        <input type="text" name="organisasi_nama_singkat" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_email" class="required-label">Email Perguruan Tinggi</label>
                                        <input type="email" name="organisasi_email" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_telp" class="required-label">No Telepon</label>
                                        <input type="text" name="organisasi_telp" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_kota" class="required-label">Kota</label>
                                        <select name="organisasi_kota" class="form-control select-search" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach ($kotas as $kota)
                                                <option value="{{ $kota->nama }}">
                                                    {{ $kota->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_alamat" class="required-label">Alamat</label>
                                        <input type="text" name="organisasi_alamat" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_website">Website (Opsional)</label>
                                        <input type="text" name="organisasi_website" class="form-control">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="parent_id">Badan Penyelenggara</label>
                                        <select name="parent_id" class="form-control select-search" required>
                                            <option value="">-- Pilih Parent Organisasi --</option>
                                            @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                                <option value="{{ $badanPenyelenggara->id }}">
                                                    {{ $badanPenyelenggara->organisasi_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_logo" class="required-label">Logo Perguruan Tinggi</label>
                                        <input type="file" name="organisasi_logo" class="form-control" required
                                            accept="image/png, image/jpg, image/jpeg, image/gif"
                                            onchange="previewLogo(event)">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                            GIF. Maksimal Ukuran File : 2 MB.</small>
                                        <img id="logo-preview" src="#" alt="Preview Logo" style="display: none;">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="organisasi_berubah_id">Tipe Perubahan</label>
                                        <select class="form-control select-search" name="perubahan" id="changeType" required>
                                            <option value="Aktif">Pendirian</option>
                                            <option value="penyatuan">Penyatuan</option>
                                            <option value="penggabungan">Penggabungan</option>
                                        </select>
                                    </div>

                                    <div id="perguruan-tinggi-select" class="hidden">
                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_1">Perguruan Tinggi 1</label>
                                            <select name="perguruan_tinggi_1" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggi)
                                                    <option value="{{ $perguruanTinggi->id }}">
                                                        {{ $perguruanTinggi->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="perguruan_tinggi_2">Perguruan Tinggi 2</label>
                                            <select name="perguruan_tinggi_2" class="form-control select-search">
                                                <option value="">-- Pilih Perguruan Tinggi --</option>
                                                @foreach ($perguruanTinggis as $perguruanTinggi)
                                                    <option value="{{ $perguruanTinggi->id }}">
                                                        {{ $perguruanTinggi->organisasi_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="additional-perguruan-tinggi" class="hidden">
                                        <button type="button" id="addPerguruanTinggi" class="btn btn-secondary">
                                            Tambah Perguruan Tinggi
                                        </button>
                                    </div>

                                    <div id="dynamic-perguruan-tinggi"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Surat Keputusan</h5>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sk_nomor" class="required-label">Nomor Surat Keputusan</label>
                                    <input type="text" name="sk_nomor" class="form-control" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                    <input type="date" name="sk_tanggal" class="form-control" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="id_jenis_surat_keputusan">Jenis Surat Keputusan</label>
                                    <select name="id_jenis_surat_keputusan" class="form-control select-search">
                                        <option value="">-- Pilih Perguruan Tinggi --</option>
                                        @foreach ($jenis as $jenis)
                                            <option value="{{ $jenis->id }}">
                                                {{ $jenis->jsk_nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                    <input type="file" name="sk_dokumen" class="form-control" required
                                        accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC, DOCX. Maksimal Ukuran File : 2 MB.</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{route('perguruan-tinggi.index')}}" type="submit" class="btn btn-primary">Keluar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © LLDIKTI 7.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Develop by Tim Kelembagaan MSIB 7
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('js')

@endsection
