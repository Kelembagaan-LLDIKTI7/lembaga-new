@extends('Layouts.Main')

@section('title', 'Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4 title">Perguruan Tinggi</h4>
        <div class="mb-3 d-flex justify-content-end gap-2">
            @can('Import Perguruan Tinggi')
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importExcel">
                    <i class="fas fa-file-import me-2"></i> Import Excel
                </button>
            @endCan
            <a href="{{ route('pt.export') }}" class="btn btn-success">
                <i class="fas fa-file-export me-2"></i> Export Excel
            </a>
            @can('Create Perguruan Tinggi')
                <a href="{{ route('perguruan-tinggi.create') }}" class="btn btn-primary">
                    Tambah Perguruan Tinggi
                </a>
            @endCan
        </div>
        <section class="datatables">
            <div class="col-12">
                <div class="card bordered">
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                            <table id="dom_jq_event" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%;">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Kota</th>
                                        <th>Peringkat Akreditasi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perguruanTinggis as $perguruanTinggi)
                                        <tr>
                                            <td></td>
                                            <td>{{ $perguruanTinggi->organisasi_kode }}</td>
                                            <td><a href="{{ route('perguruan-tinggi.show', $perguruanTinggi->id) }}">{{ $perguruanTinggi->pt_nama }}
                                                </a></td>
                                            <td>{{ $perguruanTinggi->organisasi_kota }}</td>
                                            <td>{{ $perguruanTinggi->akreditasis->first()?->peringkat_akreditasi->peringkat_nama ?? '' }}
                                            </td>
                                            <td>{{ $perguruanTinggi->organisasi_status }}</td>
                                            <td>
                                                @can('Detail Perguruan Tinggi')
                                                    <a href="{{ route('perguruan-tinggi.show', $perguruanTinggi->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        Detail
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
    </div>

    @include('Organisasi.PerguruanTinggi.Import')
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
