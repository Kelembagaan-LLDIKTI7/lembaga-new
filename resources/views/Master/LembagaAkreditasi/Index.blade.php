@extends('Layouts.Main')

@section('title', 'Lembaga Akreditasi')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Lembaga Akreditasi</h5>
                            </div>

                            <div class="table-responsive">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lembaga</th>
                                            <th>Akronim</th>
                                            <th>Logo</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lembagas as $lembaga)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $lembaga->lembaga_nama }}</td>
                                                <td>{{ $lembaga->lembaga_nama_singkat }}</td>
                                                <td>
                                                    @if ($lembaga->lembaga_logo)
                                                        <img src="{{ asset('storage/lembaga_akreditasi/' . $lembaga->lembaga_logo) }}"
                                                            alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $lembaga->lembaga_status }}</td>
                                            </tr>
                                        @endforeach
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
