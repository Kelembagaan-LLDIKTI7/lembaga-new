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
                        <a href="{{ route('program-studi.edit', $prodi->id) }}" class="btn btn-warning">edit</a>
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
                                            <th>Kode</th>
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
                                                <td>{{ $histori->prodi_kode }}</td>
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

                {{-- <section class="datatables">
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
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section> --}}

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-05">
                                    Akreditasi Program Studi
                                </h5>
                            </div>

                            <div class="table-responsive">
                                <table id="complex_header"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <a href="{{ route('akreditasi-program-studi.create', $prodi->id) }}"
                                        class="btn btn-primary btn-sm mb-2">
                                        Tambah Akreditasi
                                    </a>
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">No</th>
                                            <th colspan="2" class="text-center align-middle">Program Studi</th>
                                            <th colspan="4" class="text-center align-middle">SK Pimpinan</th>
                                            <th rowspan="2" class="text-center align-middle">Status</th>
                                        </tr>
                                        <tr>
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>No SK</th>
                                            <th>Akreditasi Tanggal</th>
                                            <th>Akreditasi Kadaluarsa</th>
                                            <th>Status Akreditasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($akreditasis as $akreditasi)
                                            @php
                                                $isExpired = \Carbon\Carbon::parse(
                                                    $akreditasi->akreditasi_tgl_akhir,
                                                )->isBefore(\Carbon\Carbon::today());
                                            @endphp
                                            <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_nama }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_jenjang }}</td>
                                                <td>{{ $akreditasi->akreditasi_sk }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($akreditasi->akreditasi_tgl_awal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($akreditasi->akreditasi_tgl_akhir)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $akreditasi->akreditasi_status }}</td>
                                                <td>{{ $akreditasi->aktif }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="btn-center mt-3">
                <a href="{{ route('perguruan-tinggi.show', $prodi->perguruanTinggi->id) }}"
                    class="btn btn-primary">Keluar</a>
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
