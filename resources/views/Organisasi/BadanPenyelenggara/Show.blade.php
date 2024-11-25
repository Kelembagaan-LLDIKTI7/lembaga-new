@extends('Layouts.Main')

@section('title', 'Detail Badan Penyelenggara')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <table class="table-borderless table">
                        <tr>
                            <th>Nama Badan Penyelenggara</th>
                            <td>{{ $badanPenyelenggaras->organisasi_nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $badanPenyelenggaras->organisasi_email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $badanPenyelenggaras->organisasi_telp }}</td>
                        </tr>
                        <tr>
                            <th>Kota</th>
                            <td>{{ $badanPenyelenggaras->organisasi_kota }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $badanPenyelenggaras->organisasi_alamat }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-end">
                        @can('Edit Badan Penyelenggara')
                            <a href="{{ route('badan-penyelenggara.edit', $badanPenyelenggaras->id) }}" class="btn btn-warning">
                                Edit
                            </a>
                        @endCan
                    </div>
                </div>
            </div>

            @if ($badanPenyelenggaras->referensi->isNotEmpty())
                @foreach ($badanPenyelenggaras->referensi as $ref)
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Badan Penyelenggara Telah Dipindah Bentuk Ke</h5>
                            <table class="table-borderless table">
                                <tr>
                                    <th>Nama Organisasi</th>
                                    <td>{{ $ref->organisasi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Singkatan</th>
                                    <td>{{ $ref->organisasi_nama_singkat }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Organisasi</th>
                                    <td>{{ $ref->organisasi_kode }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $ref->organisasi_email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $ref->organisasi_telp }}</td>
                                </tr>
                                <tr>
                                    <th>Kota</th>
                                    <td>{{ $ref->organisasi_kota }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $ref->organisasi_alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>{{ $ref->organisasi_website }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $ref->organisasi_status }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif

            @can('View Pimpinan Badan Penyelenggara')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Pimpinan Badan Penyelenggara Yang dimiliki</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="pimpinan_table" class="table-striped table-bordered display text-nowrap table border"
                                    style="overflow-x: auto; overflow-y: hidden;">
                                    @can('Create Pimpinan Badan Penyelenggara')
                                        <a href="{{ route('pimpinan-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Pimpinan BP
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">No</th>
                                            <th colspan="2" class="text-center align-middle">Jabatan</th>
                                            <th colspan="3" class="text-center align-middle">SK Akreditasi</th>
                                            <th rowspan="2" class="text-center align-middle">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>No SK</th>
                                            <th>Tanggal Terbit</th>
                                            <th>Tanggal Berakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pimpinan as $pimpinan)
                                            @php
                                                $isExpired = \Carbon\Carbon::parse(
                                                    $pimpinan->pimpinan_tanggal_berakhir,
                                                )->isBefore(\Carbon\Carbon::today());
                                            @endphp
                                            <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                                                <td></td>
                                                <td>{{ $pimpinan->pimpinan_nama }}</td>
                                                <td>{{ $pimpinan->jabatan->jabatan_nama }}</td>
                                                <td>{{ $pimpinan->pimpinan_sk }}</td>
                                                <td>{{ $pimpinan->pimpinan_tanggal }}</td>
                                                <td>{{ $pimpinan->pimpinan_tanggal_berakhir }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @can('Edit Pimpinan Badan Penyelenggara')
                                                            <a href="{{ route('pimpinan-badan-penyelenggara.edit', ['id' => $pimpinan->id]) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="ri-edit-2-line"></i> Edit
                                                            </a>
                                                        @endcan
                                                        @can('Detail Pimpinan Badan Penyelenggara')
                                                            <button class="btn btn-info btn-sm pimpinan-detail"
                                                                data-bs-toggle="modal" data-bs-target="#detailRecordModalPimpinan"
                                                                data-id="{{ $pimpinan->id }}">
                                                                Detail
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @endCan

            @can('View SK Badan Penyelenggara')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">SKBP</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="skbp" class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    @can('Create SK Badan Penyelenggara')
                                        <a href="{{ route('skbp-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah SKBP
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor SK</th>
                                            <th>Tanggal SK</th>
                                            <th>Jenis SK</th>
                                            <th>Dokumen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($skbp as $sk)
                                            <tr>
                                                <td></td>
                                                <td>{{ $sk->nomor }}</td>
                                                <td>{{ $sk->tanggal }}</td>
                                                <td>{{ $sk->jenis }}</td>
                                                <td>
                                                    @can('View PDF SK Badan Penyelenggara')
                                                        @if ($sk->dokumen)
                                                            <a href="{{ route('skbp-badan-penyelenggara.viewPdf', $sk->id) }}"
                                                                target="_blank">
                                                                Dokumen
                                                            </a>
                                                        @endif
                                                    @endCan
                                                </td>
                                                <td>
                                                    @can('Edit SK Badan Penyelenggara')
                                                        <a href="{{ route('skbp-badan-penyelenggara.edit', $sk->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            Edit
                                                        </a>
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
            @endCan

            @can('View Perguruan Tinggi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Perguruan Tinggi Yang Dimiliki</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="perguruan_tinggi"
                                    class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Perguruan Tinggi</th>
                                            <th>Nama Singkatan</th>
                                            <th>Kota</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($badanPenyelenggaras->children as $bp)
                                            <tr>
                                                <td></td>
                                                <td>{{ $bp->organisasi_nama }}</td>
                                                <td>{{ $bp->organisasi_nama_singkat }}</td>
                                                <td>{{ $bp->organisasi_kota }}</td>
                                                <td>{{ $bp->organisasi_status }}</td>
                                                <td>
                                                    @can('Detail Perguruan Tinggi')
                                                        @if ($bp->tampil == 1)
                                                            <a href="{{ route('perguruan-tinggi.show', $bp->id) }}"
                                                                class="btn btn-sm btn-primary me-2">
                                                                <i class="ti ti-info-circle"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @endCan

            @can('View Akta Badan Penyelenggara')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Akta Yang Dimiliki</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    @can('Create Akta Badan Penyelenggara')
                                        <a href="{{ route('akta-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Akta
                                        </a>
                                    @endcan
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Akta</th>
                                            <th>Nomor Tanggal</th>
                                            <th>Nomor SK Kumham</th>
                                            <th>Tanggal SK Kuham</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($akta as $akta)
                                            <tr>
                                                <td></td>
                                                <td>{{ $akta->akta_nomor }}</td>
                                                <td>{{ $akta->akta_tanggal }}</td>
                                                <td>{{ optional($akta->skKumham)->kumham_nomor ?? 'N/A' }}</td>
                                                <td>{{ optional($akta->skKumham)->kumham_tanggal ?? 'N/A' }}</td>
                                                <td>
                                                    @can('Edit Akta Badan Penyelenggara')
                                                        <div class="edit">
                                                            <a href="{{ route('akta-badan-penyelenggara.edit', $akta->id) }}"
                                                                class="btn btn-sm btn-success mb-2">Edit</a>
                                                        </div>
                                                    @endCan
                                                    @can('Create SK Kumham Badan Penyelenggara')
                                                        @if ($akta->skKumham)
                                                            <div class="kumham">
                                                                <a href="{{ route('sk-kumham.edit', $akta->id) }}"
                                                                    class="btn btn-sm btn-warning mb-2">
                                                                    SK Kumham
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="kumham">
                                                                <a href="{{ route('sk-kumham.create', $akta->id) }}"
                                                                    class="btn btn-sm btn-warning mb-2">
                                                                    SK Kumham
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endCan
                                                    @can('Detail Akta Badan Penyelenggara')
                                                        <button class="btn btn-info btn-sm akta-detail mb-2" data-bs-toggle="modal"
                                                            data-bs-target="#detailRecordModalAkta" data-id="{{ $akta->id }}">
                                                            Detail
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @endCan

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Evaluasi</h5>
                        </div>
                        <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                            <table id="perkara" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%">
                                @can('Create Perkara Badan Penyelenggara')
                                    <a href="{{ route('perkara-organisasi.create', $badanPenyelenggaras->id) }}"
                                        class="btn btn-primary btn-sm mb-2">
                                        Tambah Evaluasi
                                    </a>
                                @endCan
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>No Perkara</th>
                                        <th>Tanggal Kejadian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perkaras as $perkara)
                                        <tr>
                                            <td></td>
                                            <td>{{ $perkara->title }}</td>
                                            <td>{{ $perkara->no_perkara }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($perkara->tanggal_kejadian)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>{{ $perkara->status }}</td>
                                            <td>
                                                @can('View Detail Perkara Badan Penyelenggara')
                                                    <a href="{{ route('perkara-organisasi.show', $perkara->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        <i class="ti ti-info-circle"></i>
                                                    </a>
                                                @endCan
                                                @can('Update Status Perkara Badan Penyelenggara')
                                                    <button class="btn btn-sm btn-warning edit-status" data-bs-toggle="modal"
                                                        data-bs-target="#editStatusModal" data-id="{{ $perkara->id }}"
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
                <a href="{{ route('badan-penyelenggara.index') }}" class="btn btn-sm btn-primary float-end me-2 mt-3">
                    Kembali
                </a>
            </section>
        </div>

        @include('Modal.Bp.Edit')
        @include('Dokumen.AktaBp.Detail')
        @include('Pimpinan.BadanPenyelenggara.Detail')
    </div>

    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('#pimpinan_table').DataTable();

            $('#perguruan_tinggi').DataTable();

            $('#skbp').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#program_studi').DataTable();

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

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('pimpinan-detail')) {
                var pimpinanId = event.target.getAttribute('data-id');
                fetch('{{ route('pimpinan-badan-penyelenggara.show', ':id') }}'.replace(":id", pimpinanId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('jabatan_nama').textContent = data.jabatan_nama;
                        document.getElementById('pimpinan_nama').textContent = data.pimpinan_nama;
                        document.getElementById('pimpinan_email').textContent = data.pimpinan_email;
                        document.getElementById('pimpinan_tanggal').textContent = data.pimpinan_tanggal;
                        document.getElementById('pimpinan_tanggal_berakhir').textContent = data
                            .pimpinan_tanggal_berakhir;
                        document.getElementById('pimpinan_status').textContent = data.pimpinan_status;
                        document.getElementById('pimpinan_sk').textContent = data.pimpinan_sk;
                        if (data.pimpinan_sk_dokumen) {
                            document.getElementById('pimpinan_sk_dokumen').value = data.pimpinan_sk_dokumen;
                        } else {
                            document.getElementById('btn_pdf').hidden = true;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            if (event.target.classList.contains('akta-detail')) {
                var aktaId = event.target.getAttribute('data-id');
                fetch('{{ route('akta-badan-penyelenggara.show', ':id') }}'.replace(":id",
                        aktaId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('akta_nomor').textContent = data.akta_nomor;
                        document.getElementById('akta_jenis').textContent = data.akta_jenis;
                        document.getElementById('akta_tanggal').textContent = data
                            .akta_tanggal;
                        document.getElementById('akta_notaris_nama').textContent = data
                            .akta_nama_notaris;
                        document.getElementById('akta_notaris_kota').textContent = data.akta_kota_notaris;
                        document.getElementById('akta_keterangan').textContent = data.akta_keterangan;
                        if (data.akta_dokumen) {
                            document.getElementById('btn_pdf_akta').hidden = false;
                            document.getElementById('akta_dokumen').value = data.akta_dokumen;
                        } else {
                            document.getElementById('btn_pdf_akta').hidden = true;
                        }
                        document.getElementById('kumham_nomor').textContent = data.kumham_nomor ??
                            '';
                        document.getElementById('kumham_perihal').textContent = data.kumham_perihal ??
                            '';
                        document.getElementById('kumham_tanggal').textContent = data.kumham_tanggal ??
                            '';
                        if (data.kumham_dokumen) {
                            document.getElementById('btn_pdf_kumham').hidden = false;
                            document.getElementById('kumham_dokumen').value = data.kumham_dokumen;
                        } else {
                            document.getElementById('btn_pdf_kumham').hidden = true;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#pimpinan_table')) {
                $('#pimpinan_table').DataTable().destroy();
            }

            $('#pimpinan_table').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
            if ($.fn.DataTable.isDataTable('#perguruan_tinggi')) {
                $('#perguruan_tinggi').DataTable().destroy();
            }

            $('#perguruan_tinggi').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
            if ($.fn.DataTable.isDataTable('#skbp')) {
                $('#skbp').DataTable().destroy();
            }

            $('#skbp').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
            if ($.fn.DataTable.isDataTable('#pemimpin_perguruan_tinggi')) {
                $('#pemimpin_perguruan_tinggi').DataTable().destroy();
            }

            $('#pemimpin_perguruan_tinggi').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
            if ($.fn.DataTable.isDataTable('#program_studi')) {
                $('#program_studi').DataTable().destroy();
            }

            $('#program_studi').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
            if ($.fn.DataTable.isDataTable('#perkara')) {
                $('#perkara').DataTable().destroy();
            }

            $('#perkara').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "searchable": false,
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
        });
    </script>
@endsection
