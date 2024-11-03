@extends('Layouts.Main')

@section('title', 'Jabatan')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Jabatan</h5>
                            </div>

                            <div class="table-responsive">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jabatan</th>
                                            <th>Status</th>
                                            <th>Organisasi</th>
                                            <th>Bentuk PT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jabatans as $jabatan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jabatan->jabatan_nama }}</td>
                                                <td>{{ $jabatan->jabatan_status }}</td>
                                                <td>{{ $jabatan->jabatan_organisasi }}</td>
                                                <td>{{ $bentuk_pts[$jabatan->bentuk_pt] ?? 'Unknown' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
