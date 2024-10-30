@extends('Layouts.Main')

@section('title', 'Detail Program Studi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Program Studi</h5>
                        <div class="mb-4">
                            <h6>Informasi Program Studi</h6>
                            <table class="table-borderless table">
                                <tr>
                                    <th>Nama Program Studi</th>
                                    <td>{{ $prodi->prodi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Program</th>
                                    <td>{{ $prodi->prodi_jenjang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $prodi->prodi_active_status }}</td>
                                </tr>
                            </table>
                        </div>
                        {{-- route('perguruan-tinggi.show', $perguruanTinggi->id) --}}
                        <a href="{{route('program-studi.edit', $prodi->id)}}" class="btn btn-warning">edit</a>
                    </div>
                </div>

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Histori Program Studi</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="pemimpin_perguruan_tinggi"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>Status</th>
                                            <th>SK Nomor</th>
                                            <th>SK Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prodi->historiPerguruanTinggi as $histori)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $histori->prodi_nama }}</td>
                                                <td>{{ $histori->prodi_jenjang }}</td>
                                                <td>{{ $histori->prodi_active_status }}</td>
                                                <td>{{ $histori->sk_nomor }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($histori->sk_tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Akreditasi Yang dimiliki</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="akreditasi_table"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="overflow-x: auto; overflow-y: hidden;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor SK</th>
                                            <th>Berlaku</th>
                                            <th>Status</th>
                                            <th>Lembaga Akreditasi</th>
                                            <th>Peringkat Akreditasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($jabatans as $jabatan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $jabatan->jabatan_nama }}</td>
                                            <td>{{ $jabatan->jabatan_status }}</td>
                                            <td>{{ $jabatan->jabatan_organisasi }}</td>
                                        </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>



            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#organisasi_table').DataTable();

            $('#akreditasi_table').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();
        });
    </script>
@endsection
