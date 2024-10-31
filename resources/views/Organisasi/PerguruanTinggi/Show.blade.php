@extends('Layouts.Main')

@section('title', 'Detail Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $organisasi->organisasi_nama }}</h5>
                        <table class="table-borderless table">
                            <tr>
                                <th>Nama Singkatan</th>
                                <td>{{ $organisasi->organisasi_nama_singkat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $organisasi->organisasi_email }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $organisasi->organisasi_telp }}</td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td>{{ $organisasi->organisasi_kota }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $organisasi->organisasi_alamat }}</td>
                            </tr>
                            <tr>
                                <th>Website</th>
                                <td><a href="{{ $organisasi->organisasi_website }}">{{ $organisasi->organisasi_website }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $organisasi->organisasi_status }}</td>
                            </tr>
                            <tr>
                                <th>Logo</th>
                                <td><img src="{{ asset('storage/' . $organisasi->organisasi_logo) }}" alt="Logo"
                                        width="50" /></td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary me-2">Alih Bentuk</button>
                            <a href="{{ route('perguruan-tinggi.edit', $organisasi->id) }}" class="btn btn-warning">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Badan Penyelenggara</h5>
                        @if ($organisasi->parent)
                            <table class="table-borderless table">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $organisasi->parent->organisasi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Singkatan</th>
                                    <td>{{ $organisasi->parent->organisasi_nama_singkat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $organisasi->parent->organisasi_email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $organisasi->parent->organisasi_telp }}</td>
                                </tr>
                                <tr>
                                    <th>Kota</th>
                                    <td>{{ $organisasi->parent->organisasi_kota }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $organisasi->parent->organisasi_alamat }}</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted">Tidak ada badan penyelenggara terkait.</p>
                        @endif
                    </div>
                </div>
            </div>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        @if ($berubahOrganisasi->isNotEmpty())
                            <div class="mt-5">
                                <h6>Organisasi yang Terlibat dalam Penyatuan/Penggabungan</h6>
                                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                    <table id="organisasi_table"
                                        class="table-striped table-bordered display text-nowrap table border"
                                        style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Perguruan Tinggi</th>
                                                <th>Nama Singkat</th>
                                                <th>Email</th>
                                                <th>No Telepon</th>
                                                <th>Kota</th>
                                                <th>Alamat</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($berubahOrganisasi as $key => $org)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $org->organisasi_nama }}</td>
                                                    <td>{{ $org->organisasi_nama_singkat ?? '-' }}</td>
                                                    <td>{{ $org->organisasi_email }}</td>
                                                    <td>{{ $org->organisasi_telp }}</td>
                                                    <td>{{ $org->organisasi_kota }}</td>
                                                    <td>{{ $org->organisasi_alamat }}</td>
                                                    <td>{{ $org->organisasi_status }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mt-5">
                                Tidak ada organisasi yang terlibat dalam penyatuan atau penggabungan.
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Akreditasi Yang dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="akreditasi_table"
                                class="table-striped table-bordered display text-nowrap table border"
                                style="overflow-x: auto; overflow-y: hidden;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor SK</th>
                                        <th>Berlaku</th>
                                        <th>Status</th>
                                        <th>Lembaga Akreditasi</th>
                                        <th>Peringkat Akreditasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($akreditasi as $akre)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $akre->akreditasi_sk }}</td>
                                            <td>{{ $akre->akreditasi_tgl_akhir }}</td>
                                            <td>{{ $akre->akreditasi_status }}</td>
                                            <td>{{ $akre->lembaga_nama_singkat }}</td>
                                            <td>{{ $akre->peringkat_nama }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Sk Institusi</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="sk_table" class="table-striped table-bordered display text-nowrap table border"
                                style="overflow-x: auto; overflow-y: hidden;">
                                <a href="#" class="btn btn-primary btn-sm">
                                    Tambah SK
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor SK</th>
                                        <th>Tanggal Terbit</th>
                                        <th>Jenis Sk</th>
                                        <th>Lembaga Akreditasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($jabatans as $jabatan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $jabatan->jabatan_nama }}</td>
                                            <td>{{ $jabatan->jabatan_status }}</td>
                                            <td>{{ $jabatan->jabatan_organisasi }}</td>
                                        </tr>
                                        @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Pemimpin Perguruan Tinggi</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="pemimpin_perguruan_tinggi"
                                class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                <a href="{{ route('pimpinan-organisasi.create', ['id' => $organisasi->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    Tambah Pempinan
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Peringkat</th>
                                        <th>Logo</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($peringkats as $peringkat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $peringkat->peringkat_nama }}</td>
                                                <td>
                                                    @if ($peringkat->peringkat_logo)
                                                        <img src="{{ asset('storage/peringkat_akreditasi/' . $peringkat->peringkat_logo) }}"
                                                            alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $peringkat->peringkat_status }}</td>
                                            </tr>
                                        @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Program Studi</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%">
                                <a href="{{ route('program-studi.create', $organisasi->id) }}"
                                    class="btn btn-primary btn-sm">
                                    Tambah Program Studi
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prodi</th>
                                        <th>Program</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($organisasi->prodis as $prodi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $prodi->prodi_nama }}</td>
                                            <td>{{ $prodi->prodi_jenjang }}</td>
                                            <td>{{ $prodi->prodi_active_status }}</td>
                                            <td>
                                                <a href="{{ route('program-studi.show', $prodi->id) }}"
                                                    class="btn btn-sm btn-primary me-2">
                                                    <i class="ti ti-info-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#organisasi_table').DataTable();

            $('#akreditasi_table').DataTable();

            $('#sk_table').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#program_studi').DataTable();
        });
    </script>

@endsection
