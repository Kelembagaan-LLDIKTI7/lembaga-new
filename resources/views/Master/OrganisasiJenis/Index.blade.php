@extends('Layouts.Main')

@section('title', 'Jenis Organisasi')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Jenis Organisasi</h5>
                            </div>

                            <div class="table-responsive">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Organisasi Jenis Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organisasiTypes as $organisasiType)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $organisasiType->organisasi_type_nama }}</td>
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
