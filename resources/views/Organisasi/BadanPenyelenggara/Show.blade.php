@extends('Layouts.Main')

@section('title', 'Detail Badan Penyelenggara')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">test</h5>
                    <table class="table-borderless table">
                        <tr>
                            <th>Nama Badan Penyelenggara</th>
                            <td>{{ $badanPenyelenggaras->organisasi_nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $badanPenyelenggaras->organisasi_email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $badanPenyelenggaras->organisasi_telp }}</td>
                        </tr>
                        <tr>
                            <th>Kota</th>
                            <td>{{ $badanPenyelenggaras->organisasi_kota }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $badanPenyelenggaras->organisasi_alamat }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-end">
                        <a href="" class="btn btn-primary me-2">Alih Bentuk</a>
                        <a href="" class="btn btn-warning">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Pimpinan Badan Penyelenggara Yang dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="pimpinan_table" class="table-striped table-bordered display text-nowrap table border"
                                style="overflow-x: auto; overflow-y: hidden;">
                                <a href="{{ route('pimpinan-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                    class="btn btn-primary btn-sm mb-2">
                                    Tambah Pimpinan BP
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pimpinan as $pimpinan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pimpinan->pimpinan_nama }}</td>
                                            <td>{{ $pimpinan->pimpinan_email }}</td>
                                            <td>{{ $pimpinan->pimpinan_status }}</td>
                                            <td>{{ $pimpinan->jabatan->jabatan_nama }}</td>
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
                            <h5 class="mb-0">Perguruan Tinggi Yang Dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="perguruan_tinggi"
                                class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Perguruan Tinggi</th>
                                        <th>Nama Singkatan</th>
                                        <th>Kota</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($badanPenyelenggaras->children as $bp)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bp->organisasi_nama }}</td>
                                            <td>{{ $bp->organisasi_nama_singkat }}</td>
                                            <td>{{ $bp->organisasi_kota }}</td>
                                            <td>{{ $bp->organisasi_status }}</td>
                                            <td>
                                                <a href="{{ route('perguruan-tinggi.show', $bp->id) }}"
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

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Akta Yang Dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%">
                                <a href="" class="btn btn-primary btn-sm mb-2">
                                    Tambah Akta
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Akta</th>
                                        <th>Status Akta</th>
                                        <th>Nomor SK Kumham</th>
                                        <th>Tanggal SK Kuham</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($organisasi->prodis as $prodi)
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
                                    @endforeach --}}
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

            $('#pimpinan_table').DataTable();

            $('#perguruan_tinggi').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#program_studi').DataTable();
        });
    </script>

@endsection
