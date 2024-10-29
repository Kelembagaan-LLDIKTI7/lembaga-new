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
                                            <th>Jabatan Status</th>
                                            <th>Jabatan Kategori Organisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jabatans as $jabatan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jabatan->jabatan_nama }}</td>
                                                <td>{{ $jabatan->jabatan_status }}</td>
                                                <td>{{ $jabatan->jabatan_organisasi }}</td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
