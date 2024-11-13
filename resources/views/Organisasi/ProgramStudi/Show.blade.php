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
                                <tr>
                                    <th>Nomor SK Ijin Prodi</th>
                                    <td>{{ $sk->sk_nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal SK Prodi</th>
                                    <td>{{ \Carbon\Carbon::parse($sk->sk_tanggal)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Periode Awal Pelaporan Dikti</th>
                                    <td>{{ \Carbon\Carbon::parse($sk->sk_tanggal)->translatedFormat('d F Y') }}</td>
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
                                            <th colspan="3" class="text-center align-middle">Program Studi</th>
                                            <th colspan="4" class="text-center align-middle">Akreditasi Program Studi</th>
                                            <th rowspan="2" class="text-center align-middle">Status</th>
                                        </tr>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>No SK</th>
                                            <th>Peringkat Akreditasi</th>
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
                                                <td>{{ $akreditasi->prodi->prodi_kode }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_nama }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_jenjang }}</td>
                                                <td>{{ $akreditasi->akreditasi_sk }}</td>
                                                <td>
                                                @if ($akreditasi->peringkat_akreditasi->peringkat_logo)
                                                        <img src="{{ asset('storage/peringkat_akreditasi/' . $akreditasi->peringkat_akreditasi->peringkat_logo) }}"
                                                            alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
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

                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Perkara</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="perkara" class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    @can('Create Perkara Program Studi')
                                    <a href="{{ route('perkara-prodi.create', $prodi->id) }}"
                                        class="btn btn-primary btn-sm mb-2">
                                        Tambah Perkara
                                    </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Tanggal Kejadian</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perkaras as $perkara)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $perkara->title }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($perkara->tanggal_kejadian)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $perkara->status }}</td>
                                                <td>
                                                    @can('View Detail Perkara Program Studi')
                                                    <a href="{{ route('perkara-prodi.show', $perkara->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        <i class="ti ti-info-circle"></i>
                                                    </a>
                                                    @endCan
                                                    @can('Update Status Perkara Program Studi')
                                                    <button class="btn btn-sm btn-warning edit-status"
                                                        data-bs-toggle="modal" data-bs-target="#editStatusModal"
                                                        data-id="{{ $perkara->id }}"
                                                        data-status="{{ $perkara->status }}">
                                                        Edit Status
                                                    </button>
                                                    @endCan
                                                </td>
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
            @include('Modal.Bp.Edit')
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#organisasi_table').DataTable();

            $('#akreditasi_table').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#perkara').DataTable();
        });
    </script>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('edit-status')) {
                const perkaraId = event.target.getAttribute('data-id');
                const currentStatus = event.target.getAttribute('data-status');
                document.getElementById('perkaraId').value = perkaraId;
                const statusSelect = document.getElementById('status');
                Array.from(statusSelect.options).forEach(option => {
                    option.selected = option.value === currentStatus;
                });

                document.getElementById('editStatusForm').action = `/perkara-organisasi/${perkaraId}/status-update`;
            }
        });
    </script>
@endsection
