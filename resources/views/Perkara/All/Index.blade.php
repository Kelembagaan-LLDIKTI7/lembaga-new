@extends('Layouts.Main')

@section('title', 'Perkara')

@section('content')
<section class="datatables">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <h5 class="mb-0">Evaluasi Badan Penyelenggara dan Perguruan Tinggi</h5>
                </div>

                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                    <table id="dom_jq_event_org" class="table-striped table-bordered display text-nowrap table border"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Tanggal Kejadian</th>
                                <th>Instansi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perkarasOrg as $perkara)
                            <tr>
                                <td></td>
                                <td>{{ $perkara->title }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($perkara->tanggal_kejadian)->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    {{ $perkara->organisasi->organisasi_nama }}
                                </td>
                                <td>{{ $perkara->status }}</td>
                                <td>
                                    <a href="{{ route('perkara.show', $perkara->id) }}"
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
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <h5 class="mb-0">Evaluasi Program Studi</h5>
                </div>

                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                    <table id="dom_jq_event_prodi" class="table-striped table-bordered display text-nowrap table border"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Tanggal Kejadian</th>
                                <th>Instansi</th>
                                <th>Prodi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perkarasProdi as $perkara)
                            <tr>
                                <td></td>
                                <td>{{ $perkara->title }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($perkara->tanggal_kejadian)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ $perkara->organisasi_nama }}</td>
                                <td>{{ $perkara->prodi_nama }}</td>
                                <td>{{ $perkara->status }}</td>
                                <td>
                                    <a href="{{ route('perkara.showprodi', $perkara->id) }}"
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
@endsection

@section('js')
{{-- <script>
    $(document).ready(function() {
        $('#dom_jq_event_org').DataTable();
        $('#dom_jq_event_prodi').DataTable();
    });
</script> --}}
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#dom_jq_event_org')) {
            $('#dom_jq_event_org').DataTable().destroy();
        }

        $('#dom_jq_event_org').DataTable({
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
        if ($.fn.DataTable.isDataTable('#dom_jq_event_prodi')) {
            $('#dom_jq_event_prodi').DataTable().destroy();
        }

        $('#dom_jq_event_prodi').DataTable({
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
