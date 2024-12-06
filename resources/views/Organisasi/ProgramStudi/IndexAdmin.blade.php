@extends('Layouts.Main')

@section('title', 'Perguruan Tinggi')

@section('content')
    <section class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <h5 class="mb-0">Program Studi</h5>
                </div>
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    @can('Export Program Studi')
                        <a href="{{ route('prodi.export') }}" class="btn btn-success btn-sm me-2">
                            Export Excel
                        </a>
                    @endCan
                </div>
                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                    <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode PT</th>
                                <th>Nama PT</th>
                                <th>Kode Prodi</th>
                                <th>Nama Prodi</th>
                                <th>Program</th>
                                <th>Periode Awal Pelaporan PDDIKTI</th>
                                <th>Status</th>
                                <th>Peringkat Akreditasi</th>
                                <th>SK Akreditasi</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prodis as $prodi)
                                <tr>
                                    <td></td>
                                    <td>{{ $prodi->kode_pt }}</td>
                                    <td>{{ $prodi->nama_pt }}</td>
                                    <td>{{ $prodi->prodi_kode }}</td>
                                    <td>{{ $prodi->prodi_nama }}</td>
                                    <td>{{ $prodi->prodi_jenjang }}</td>
                                    <td> @php
                                        $periode = $prodi->prodi_periode; // Get the full periode value
                                        $lastDigit = substr($periode, -1); // Extract the last digit
                                        $newPeriode = substr($periode, 0, -1); // Remove the last digit
                                        if ($lastDigit == '1') {
                                            $newPeriode .= ' Gasal'; // Append 'gasal'
                                        } elseif ($lastDigit == '2') {
                                            $newPeriode .= ' Genap'; // Append 'genap'
                                        } else {
                                            $newPeriode .= $lastDigit; // Keep the original digit for other cases
                                        }
                                    @endphp

                                        {{ $newPeriode }}</td>
                                    <td>{{ $prodi->status }}</td>
                                    <td>
                                        {{ $prodi->akreditasi ?? '' }}
                                    </td>
                                    <td>{{ $prodi->no_sk_akreditasi ?? '' }}</td>
                                    <td>{{ $prodi->tgl_akhir_sk_akreditasi ?? '' }}</td>
                                    <td>
                                        @can('Detail Program Studi')
                                            <a href="{{ route('program-studi.show', $prodi->id) }}"
                                                class="btn btn-sm btn-primary me-2">
                                                <i class="ti ti-info-circle"></i>
                                            </a>
                                        @endcan
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
    </section>
@endsection
{{-- @section('js')
    <script>
        $(document).ready(function() {
            $('#program_studi').DataTable();
        });
    </script>
@endsection --}}
@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#program_studi')) {
                $('#program_studi').DataTable().destroy();
            }

            $('#program_studi').DataTable({
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
