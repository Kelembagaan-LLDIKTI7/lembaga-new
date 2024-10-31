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

                            <div class="table-responsive">
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
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_nama }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_email }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_telp }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_kota }}</td>
                                                <td>{{ $badanPenyelenggara->organisasi_status }}</td>
                                                <td>
                                                    <a href="{{ route('badan-penyelenggara.show', $badanPenyelenggara->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        <i class="ti ti-info-circle"></i>
                                                    </a>
                                                </td>
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
