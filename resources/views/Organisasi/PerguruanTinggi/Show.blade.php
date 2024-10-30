@extends('Layouts.Main')

@section('title', 'Detail Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Perguruan Tinggi</h5>
                        <div class="mb-4">
                            <h6>Informasi Perguruan Tinggi</h6>
                            <table class="table-bordered table">
                                <tr>
                                    <th>Nama Perguruan Tinggi</th>
                                    <td>{{ $organisasi->organisasi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Singkat</th>
                                    <td>{{ $organisasi->organisasi_nama_singkat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $organisasi->organisasi_email }}</td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td>{{ $organisasi->organisasi_telp }}</td>
                                </tr>
                                <tr>
                                    <th>Kota</th>
                                    <td>{{ $organisasi->organisasi_kota }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $organisasi->organisasi_alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $organisasi->organisasi_status }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            @if ($berubahOrganisasi->isNotEmpty())
                                <div class="mt-5">
                                    <h6>Organisasi yang Terlibat dalam Penyatuan/Penggabungan</h6>
                                    <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                        <table id="organisasi_table"
                                            class="table-striped table-bordered display text-nowrap table border"
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Perguruan Tinggi</th>
                                                    <th>Nama Singkat</th>
                                                    <th>Email</th>
                                                    <th>No Telepon</th>
                                                    <th>Kota</th>
                                                    <th>Alamat</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($berubahOrganisasi as $key => $org)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $org->organisasi_nama }}</td>
                                                        <td>{{ $org->organisasi_nama_singkat ?? '-' }}</td>
                                                        <td>{{ $org->organisasi_email }}</td>
                                                        <td>{{ $org->organisasi_telp }}</td>
                                                        <td>{{ $org->organisasi_kota }}</td>
                                                        <td>{{ $org->organisasi_alamat }}</td>
                                                        <td>{{ $org->organisasi_status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mt-5">
                                    Tidak ada organisasi yang terlibat dalam penyatuan atau penggabungan.
                                </div>
                            @endif
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

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Pemimpin Perguruan Tinggi</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="pemimpin_perguruan_tinggi"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Peringkat</th>
                                            <th>Logo</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($peringkats as $peringkat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $peringkat->peringkat_nama }}</td>
                                                <td>
                                                    @if ($peringkat->peringkat_logo)
                                                        <img src="{{ asset('storage/peringkat_akreditasi/' . $peringkat->peringkat_logo) }}"
                                                            alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $peringkat->peringkat_status }}</td>
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
