@extends('Layouts.Main')

@section('title', 'Perguruan Tinggi')

@section('content')
    <section class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <h5 class="mb-0">Program Studi</h5>
                </div>
                <a href="{{ route('prodi.export') }}" class="btn btn-success btn-sm me-2">
                    Export Excel
                </a>
                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                    <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Prodi</th>
                                <th>Program</th>
                                <th>Periode</th>
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $prodi->prodi_kode }}</td>
                                    <td>{{ $prodi->prodi_nama }}</td>
                                    <td>{{ $prodi->prodi_jenjang }}</td>
                                    <td>{{ $prodi->prodi_periode }}</td>
                                    <td>{{ $prodi->prodi_active_status }}</td>
                                    <td>
                                        @if (
                                            !empty($prodi->akreditasis) &&
                                                $prodi->akreditasis->first() &&
                                                $prodi->akreditasis->first()->peringkat_akreditasi &&
                                                $prodi->akreditasis->first()->peringkat_akreditasi->peringkat_logo)
                                            <img src="{{ asset('storage/peringkat_akreditasi/' . $prodi->akreditasis->first()->peringkat_akreditasi->peringkat_logo) }}"
                                                alt="Logo" width="50" height="50">
                                        @else
                                            <span>No Logo</span>
                                        @endif
                                    </td>
                                    <td>{{ $prodi->akreditasis->first()->akreditasi_sk ?? 'Tidak Tersedia' }}</td>
                                    <td>{{ $prodi->akreditasis->first()->akreditasi_tgl_akhir ?? 'Tidak Tersedia' }}</td>
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
@section('js')
    <script>
        $(document).ready(function() {
            $('#program_studi').DataTable();
        });
    </script>
@endsection
