@extends('Layouts.Main')

@section('title', 'Perguruan Tinggi')

@section('content')
    <section class="datatables">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">
                        <h5 class="mb-0">Perguruan Tinggi</h5>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                        <table id="dom_jq_event" class="table-striped table-bordered display text-nowrap table border"
                            style="width: 100%">
                            <div class="mb-3">
                                @can('Import Perguruan Tinggi')
                                <button class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#importExcel">
                                    Import Excel
                                </button>
                                @endCan
                                @can('Create Perguruan Tinggi')
                                <a href="{{ route('perguruan-tinggi.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Perguruan Tinggi
                                </a>
                                @endCan
                            </div>

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Perguruan Tinggi</th>
                                    <th>Nama Perguruan Tinggi</th>
                                    <th>Akronim PT</th>
                                    <th>Alamat Email</th>
                                    <th>No Telepon</th>
                                    <th>Kota PT</th>
                                    <th>Status PT</th>
                                    <th>Nama Badan Penyelenggara</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($perguruanTinggis as $perguruanTinggi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_kode }}</td>
                                        <td>{{ $perguruanTinggi->pt_nama }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_nama_singkat }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_email }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_telp }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_kota }}</td>
                                        <td>{{ $perguruanTinggi->organisasi_status }}</td>
                                        <td>{{ $perguruanTinggi->parent->organisasi_nama ?? '-' }}</td>
                                        <td>
                                            @can('Detail Perguruan Tinggi')
                                            <a href="{{ route('perguruan-tinggi.show', $perguruanTinggi->id) }}"
                                                class="btn btn-sm btn-primary me-2">
                                                <i class="ti ti-info-circle"></i>
                                            </a>
                                            @endCan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
    </section>

    @include('Organisasi.PerguruanTinggi.Import')
@endsection
