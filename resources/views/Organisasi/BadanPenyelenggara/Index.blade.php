@extends('Layouts.Main')

@section('title', 'Badan Penyelenggara')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Badan Penyelenggara</h5>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                @can('Import Badan Penyelenggara')
                                    <button class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                                        data-bs-target="#importExcel">
                                        Import Excel
                                    </button>
                                @endCan
                                @can('Create Badan Penyelenggara')
                                    <a href="{{ route('badan-penyelenggara.create') }}" class="btn btn-primary btn-sm">
                                        Tambah Badan Penyelenggara
                                    </a>
                                @endCan
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama BP</th>
                                            <th>Email BP</th>
                                            <th>Telepon BP</th>
                                            <th>Kota BP</th>
                                            <th>Status BP</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($badanPenyelenggaras as $badanPenyelenggara)
                                            <tr>
                                                <td></td>
                                                <td><a
                                                        href="{{ route('badan-penyelenggara.show', $badanPenyelenggara->id) }}">{{ $badanPenyelenggara->organisasi_nama }}</a>
                                                </td>
                                                <td>{{ $badanPenyelenggara->organisasi_email }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_telp }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_kota }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_status }}</td>
                                                <td>
                                                    @can('Detail Badan Penyelenggara')
                                                        <a href="{{ route('badan-penyelenggara.show', $badanPenyelenggara->id) }}"
                                                            class="btn btn-sm btn-primary me-2">
                                                            <i class="ti ti-info-circle"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
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

        @include('Organisasi.BadanPenyelenggara.Import')
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#dom_jq_event')) {
                $('#dom_jq_event').DataTable().destroy();
            }

            $('#dom_jq_event').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
        });
    </script>
@endsection
