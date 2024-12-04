@extends('Layouts.Main')

@section('title', 'Detail Program Studi')

@section('css')
    <style>
        #custom-padding th,
        #custom-padding td {
            padding: 10px 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('perguruan-tinggi.show', $prodi->perguruanTinggi->id) }}"> 
            <i class="fas fa-arrow-left mb-4 me-2"></i> Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <div class="card bordered">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Detail Program Studi</h5>
                                <h6>Informasi Program Studi</h6>
                            </div>
                            <div>
                                <a href="{{ route('program-studi.edit', $prodi->id) }}" class="btn btn-primary"><i
                                        class="fas fa-edit me-2"></i> Edit</a>
                            </div>
                        </div>
                        <div class="mb-4">
                            <table id="custom-padding" class="table-borderless table">
                                <tr>
                                    <th>Nama Program Studi</th>
                                    <td>{{ $prodi->prodi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Program</th>
                                    <td>{{ $prodi->prodi_jenjang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Periode Pelaporan Awal</th>
                                    <td>
                                        @php
                                            $periode = $prodi->prodi_periode; // Get the full periode value
                                            $lastDigit = substr($periode, -1); // Extract the last digit
                                            $newPeriode = substr($periode, 0, -1); // Remove the last digit
                                            if ($lastDigit == '1') {
                                                $newPeriode .= ' Gasal'; // Append 'gasal'
                                            } elseif ($lastDigit == '2') {
                                                $newPeriode .= ' Genap'; // Append 'genap'
                                            } else {
                                                $newPeriode .= $lastDigit; // Keep the original digit for other cases
                                            }
                                        @endphp

                                        {{ $newPeriode }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>{{ $prodi->prodi_active_status }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor SK Ijin Prodi</th>
                                    <td>{{ $prodi->suratKeputusan->sk_nomor }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal SK Prodi</th>
                                    <td>{{ \Carbon\Carbon::parse($prodi->suratKeputusan->sk_tanggal)->translatedFormat('d F Y') ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dokumen SK</th>
                                    @if ($prodi->suratKeputusan->sk_dokumen)
                                        <td>
                                            <a href="{{ asset('storage/' . $prodi->suratKeputusan->sk_dokumen) }}"
                                                target="_blank">Dokumen</a>
                                        </td>
                                    @else
                                        <td>-</td>
                                    @endif
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <section class="datatables">
                    <div class="card bordered">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Histori Program Studi</h5>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="pemimpin_perguruan_tinggi"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prodi->historiPerguruanTinggi as $histori)
                                            <tr>
                                                <td></td>
                                                <td>{{ $histori->prodi_kode }}</td>
                                                <td>{{ $histori->prodi_nama }}</td>
                                                <td>{{ $histori->prodi_jenjang }}</td>
                                                <td>
                                                    @php
                                                        $periode = $prodi->prodi_periode; // Get the full periode value
                                                        $lastDigit = substr($periode, -1); // Extract the last digit
                                                        $newPeriode = substr($periode, 0, -1); // Remove the last digit
                                                        if ($lastDigit == '1') {
                                                            $newPeriode .= ' Gasal'; // Append 'gasal'
                                                        } elseif ($lastDigit == '2') {
                                                            $newPeriode .= ' Genap'; // Append 'genap'
                                                        } else {
                                                            $newPeriode .= $lastDigit; // Keep the original digit for other cases
                                                        }
                                                    @endphp

                                                    {{ $newPeriode }}
                                                </td>
                                                <td>{{ $histori->prodi_active_status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="datatables">
                    <div class="card bordered">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-4">Akreditasi Program Studi</h5>
                                <div>
                                    <a href="{{ route('akreditasi-program-studi.create', $prodi->id) }}"
                                        class="btn btn-primary">
                                        Tambah Akreditasi
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="complex_header"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">No</th>
                                            <th colspan="3" class="text-center align-middle">Program Studi</th>
                                            <th colspan="4" class="text-center align-middle">Akreditasi Program Studi
                                            </th>
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
                                            <tr class="{{ $isExpired ? 'table-danger' : '' }}"
                                                data-id="{{ $akreditasi->id }}">
                                                <td></td>
                                                <td>{{ $akreditasi->prodi->prodi_kode }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_nama }}</td>
                                                <td>{{ $akreditasi->prodi->prodi_jenjang }}</td>
                                                <td>{{ $akreditasi->akreditasi_sk }}</td>
                                                <td>
                                                    {{ $akreditasi->peringkat_akreditasi->peringkat_nama }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($akreditasi->akreditasi_tgl_akhir)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $akreditasi->akreditasi_status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                @can('View SK Program Studi')
                    <section class="datatables">
                        <div class="card bordered">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title mb-4">Sk Program Studi</h5>
                                    <div>
                                        @can('Create SK Program Studi')
                                            <a href="{{ route('sk-program-studi.create', $prodi->id) }}" class="btn btn-primary">
                                                Tambah SK
                                            </a>
                                        @endCan
                                    </div>
                                </div>
                                <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                    <table id="sk_table" class="table-striped table-bordered display text-nowrap table border"
                                        style="overflow-x: auto; overflow-y: hidden;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SK</th>
                                                <th>Tanggal Terbit</th>
                                                <th>Jenis Sk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sk as $sk)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $sk->sk_nomor }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($sk->sk_tanggal)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td>{{ $sk->jenisSuratKeputusan->jsk_nama }}</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            @can('Edit SK Program Studi')
                                                                <div class="edit">
                                                                    <a href="{{ route('sk-program-studi.edit', $sk->id) }}"
                                                                        class="btn btn-sm btn-warning">Edit</a>
                                                                </div>
                                                            @endCan
                                                            @can('Detail SK Program Studi')
                                                                <div class="detail">
                                                                    <button class="btn btn-sm btn-primary detail-item-btn sk-detail"
                                                                        data-bs-toggle="modal" data-bs-target="#detailRecordModalSK"
                                                                        data-id="{{ $sk->id }}">Detail</button>
                                                                </div>
                                                            @endCan
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

                <section class="datatables">
                    <div class="card bordered">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-4">Evaluasi</h5>
                                <div>
                                    @can('Create Perkara Program Studi')
                                        <a href="{{ route('perkara-prodi.create', $prodi->id) }}" class="btn btn-primary">
                                            Tambah Evaluasi
                                        </a>
                                    @endCan
                                </div>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="perkara" class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
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
                                                    @can('Update Status Perkara Program Studi')
                                                        <button class="btn btn-sm btn-warning edit-status me-2"
                                                            data-bs-toggle="modal" data-bs-target="#editStatusModal"
                                                            data-id="{{ $perkara->id }}"
                                                            data-status="{{ $perkara->status }}">
                                                            Edit
                                                        </button>
                                                    @endCan
                                                    @can('View Detail Perkara Program Studi')
                                                        <a href="{{ route('perkara-prodi.show', $perkara->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            Detail
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
            </div>
            <div class="btn-center mt-3">
            </div>
            @include('Modal.Bp.Edit')
            @include('Akreditasi.ProgramStudi.Detail')
            @include('SK.ProgramStudi.Detail')
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#organisasi_table').DataTable();

            $('#akreditasi_table').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#sk_table').DataTable();

            $('#perkara').DataTable();

            var hasAkreditasiDokumenPermission = @json(auth()->user()->can('View PDF Akreditasi Perguruan Tinggi'));

            $('#complex_header tbody').on('click', 'tr', function() {
                const akreditasiId = $(this).data('id'); // Pastikan setiap <tr> memiliki data-id

                // Fetch data menggunakan jQuery AJAX
                $.ajax({
                    url: '{{ route('akreditasi-program-studi.show', ':id') }}'
                        .replace(':id', akreditasiId),
                    method: 'GET',
                    success: function(data) {
                        // Mengisi data ke modal
                        $('#org_nama').text(data.organisasi_nama);
                        $('#prodi_nama').text(data.prodi_nama);
                        $('#lembaga_nama').text(data.lembaga_nama);
                        $('#peringkat_nama').text(data.peringkat_nama);
                        $('#akreditasi_sk').text(data.akreditasi_sk);
                        $('#akreditasi_status').text(data.akreditasi_status);
                        $('#akreditasi_tgl_awal').text(data.akreditasi_tgl_awal);
                        $('#akreditasi_tgl_akhir').text(data.akreditasi_tgl_akhir);

                        if (data.akreditasi_dokumen) {
                            if (hasAkreditasiDokumenPermission) {
                                $('#akreditasi_dokumen').val(data.akreditasi_dokumen);
                            } else {
                                $('#akreditasi_dokumen').val('Access Denied');
                            }
                        } else {
                            // Menghapus btn PDF jika tidak ada dokumen
                            $('#btn_pdf').remove();
                        }

                        $('#editBtn').attr('href',
                            `/akreditasi-program-studi/${akreditasiId}/edit`);

                        // Tampilkan modal
                        $('#detailRecordModalAkreditasi').modal('show');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
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
            if (event.target.classList.contains('sk-detail')) {
                var skId = event.target.getAttribute('data-id');
                fetch('{{ route('sk-program-studi.show', ':id') }}'.replace(":id", skId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('sk_nomor').textContent = data.sk_nomor;
                        document.getElementById('sk_tanggal').textContent = data.sk_tanggal;
                        document.getElementById('jsk_nama').textContent = data.jsk_nama;

                        const skDokumenElement = document.getElementById('sk_dokument');
                        if (data.sk_dokumen) {
                            skDokumenElement.href = '{{ asset('storage') }}/' + data.sk_dokumen;
                            skDokumenElement.textContent = 'Lihat Dokumen';
                            skDokumenElement.style.display = 'inline';
                        } else {
                            skDokumenElement.style.display = 'none';
                        }
                    })
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#organisasi_table')) {
                $('#organisasi_table').DataTable().destroy();
            }

            $('#organisasi_table').DataTable({
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
            if ($.fn.DataTable.isDataTable('#akreditasi_table')) {
                $('#akreditasi_table').DataTable().destroy();
            }

            $('#akreditasi_table').DataTable({
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
            if ($.fn.DataTable.isDataTable('#sk_table')) {
                $('#sk_table').DataTable().destroy();
            }

            $('#sk_table').DataTable({
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
