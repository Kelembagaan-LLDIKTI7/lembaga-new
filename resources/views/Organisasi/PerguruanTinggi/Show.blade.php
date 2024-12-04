@extends('Layouts.Main')

@section('title', 'Detail Perguruan Tinggi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $organisasi->organisasi_nama }}</h5>
                        <table class="table-borderless table">
                            <tr>
                                <th>Kode PT</th>
                                <td>{{ $organisasi->organisasi_kode ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Bentuk PT</th>
                                <td>{{ $organisasi->bentukPt->bentuk_nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Singkatan</th>
                                <td>{{ $organisasi->organisasi_nama_singkat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $organisasi->organisasi_email }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
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
                                <th>Website</th>
                                <td><a href="{{ $organisasi->organisasi_website }}">{{ $organisasi->organisasi_website }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $organisasi->organisasi_status }}</td>
                            </tr>
                            <tr>
                                <th>Logo</th>
                                <td><img src="{{ asset('storage/' . $organisasi->organisasi_logo) }}" alt="Logo"
                                        width="50" /></td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-end">
                            @can('Edit Perguruan Tinggi')
                                <a href="{{ route('perguruan-tinggi.edit', $organisasi->id) }}" class="btn btn-warning me-2">
                                    Edit
                                </a>
                                <a href="{{ route('perguruan-tinggi.editPenyatuan', $organisasi->id) }}"
                                    class="btn btn-warning">
                                    Edit Penyatuan
                                </a>
                            @endCan
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Badan Penyelenggara</h5>
                        @if ($organisasi->parent)
                            <table class="table-borderless table">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $organisasi->parent->organisasi_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Singkatan</th>
                                    <td>{{ $organisasi->parent->organisasi_nama_singkat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $organisasi->parent->organisasi_email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $organisasi->parent->organisasi_telp }}</td>
                                </tr>
                                <tr>
                                    <th>Kota</th>
                                    <td>{{ $organisasi->parent->organisasi_kota }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $organisasi->parent->organisasi_alamat }}</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted">Tidak ada badan penyelenggara terkait.</p>
                        @endif
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
                                                <th>Kode PT</th>
                                                <th>Nama PT</th>
                                                <th>Kota</th>
                                                <th>Jenis Perubahan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($berubahOrganisasi as $key => $org)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $org->organisasi_kode }}</td>
                                                    <td>{{ $org->organisasi_nama }}</td>
                                                    <td>{{ $org->organisasi_kota }}</td>
                                                    <td>{{ $org->organisasi_berubah_status }}</td>
                                                    <td>
                                                        <a href="{{ route('perguruan-tinggi.show', $org->id) }}"
                                                            class="btn btn-sm btn-info">Detail</a>
                                                    </td>
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

            @can('View Akreditasi Perguruan Tinggi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Akreditasi Institusi</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="akreditasi_table"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="overflow-x: auto; overflow-y: hidden;">
                                    @can('Create Akreditasi Perguruan Tinggi')
                                        <a href="{{ route('akreditasi-perguruan-tinggi.create', $organisasi->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Areditasi PT
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor SK</th>
                                            <th>Tanggal Berlaku</th>
                                            <th>Lembaga Akreditasi</th>
                                            <th>Peringkat Akreditasi</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($akreditasi as $akre)
                                            @php
                                                $isExpired = \Carbon\Carbon::parse(
                                                    $akre->akreditasi_tgl_akhir,
                                                )->isBefore(\Carbon\Carbon::today());
                                            @endphp
                                            <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                                                <td></td>
                                                <td>{{ $akre->akreditasi_sk }}</td>
                                                <td>{{ $akre->akreditasi_tgl_akhir }}</td>
                                                <td>{{ $akre->lembaga_nama_singkat }}</td>
                                                <td>{{ $akre->peringkat_nama }}</td>
                                                <td>{{ $akre->akreditasi_status }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('Edit Akreditasi Perguruan Tinggi')
                                                            <div class="edit">
                                                                <a href="{{ route('akreditasi-perguruan-tinggi.edit', $akre->id) }}"
                                                                    class="btn btn-sm btn-success">Edit</a>
                                                            </div>
                                                        @endCan
                                                        @can('Detail Akreditasi Perguruan Tinggi')
                                                            <div class="detail">
                                                                <button
                                                                    class="btn btn-sm btn-info detail-item-btn akreditasi-detail"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#detailRecordModalAkreditasi"
                                                                    data-id="{{ $akre->id }}">Detail</button>
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

            @can('View SK Perguruan Tinggi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Sk Institusi</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="sk_table" class="table-striped table-bordered display text-nowrap table border"
                                    style="overflow-x: auto; overflow-y: hidden;">
                                    @can('Create SK Perguruan Tinggi')
                                        <a href="{{ route('sk-perguruan-tinggi.create', $organisasi->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah SK
                                        </a>
                                    @endCan
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
                                                <td>{{ $sk->jsk_nama }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('Edit SK Perguruan Tinggi')
                                                            <div class="edit">
                                                                <a href="{{ route('sk-perguruan-tinggi.edit', $sk->id) }}"
                                                                    class="btn btn-sm btn-success">Edit</a>
                                                            </div>
                                                        @endCan
                                                        @can('Detail SK Perguruan Tinggi')
                                                            <div class="detail">
                                                                <button class="btn btn-sm btn-info detail-item-btn sk-detail"
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

            @can('View Pimpinan Perguruan Tinggi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Pemimpin Perguruan Tinggi</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="pemimpin_perguruan_tinggi"
                                    class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                    @can('Create Pimpinan Perguruan Tinggi')
                                        <a href="{{ route('pimpinan-perguruan-tinggi.create', $organisasi->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Pempinan
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">No</th>
                                            <th colspan="2" class="text-center align-middle">Jabatan</th>
                                            <th colspan="3" class="text-center align-middle">SK Pimpinan</th>
                                            <th rowspan="2" class="text-center align-middle">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>No SK</th>
                                            <th>Tgl. Mulai Penugasan</th>
                                            <th>Tgl. Selesai Penugasan</th>
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
                                                        @can('Edit Pimpinan Perguruan Tinggi')
                                                            <a href="{{ route('pimpinan-perguruan-tinggi.edit', ['id' => $pimpinan->id]) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="ri-edit-2-line"></i> Edit
                                                            </a>
                                                        @endCan
                                                        @can('Detail Pimpinan Perguruan Tinggi')
                                                            <button class="btn btn-info btn-sm pimpinan-detail"
                                                                data-bs-toggle="modal" data-bs-target="#detailRecordModalPimpinan"
                                                                data-id="{{ $pimpinan->id }}">
                                                                Detail
                                                            </button>
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

            @can('View Program Studi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Program Studi</h5>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="program_studi"
                                    class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                    @can('Create Program Studi')
                                        <a href="{{ route('program-studi.create', $organisasi->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Program Studi
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>Periode Awal Pelaporan PDDIKTI</th>
                                            <th>Status</th>
                                            <th>Peringkat Akreditasi</th>
                                            {{-- <th>SK Akreditasi</th>
                                            <th>Tanggal Kadaluarsa</th> --}}
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organisasi->prodis as $prodi)
                                            <tr>
                                                <td></td>
                                                <td>{{ $prodi->prodi_kode }}</td>
                                                <td>{{ $prodi->prodi_nama }}</td>
                                                <td>{{ $prodi->prodi_jenjang }}</td>
                                                <td> @php
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

                                                    {{ $newPeriode }}</td>
                                                <td>{{ $prodi->prodiStatus ? $prodi->prodiStatus->prodi_status_nama : 'Status Tidak Tersedia' }}</td>
                                                @php
                                                    $akreditasi = $prodi->akreditasis->last();
                                                @endphp
                                                <td>
                                                    {{ $akreditasi->peringkat_akreditasi->peringkat_nama ?? 'Tidak Tersedia' }}
                                                </td>
                                                {{-- <td>{{ $akreditasi->akreditasi_sk ?? 'Tidak Tersedia' }}</td>
                                                <td>{{ $akreditasi->akreditasi_tgl_akhir ?? 'Tidak Tersedia' }}</td> --}}
                                                <td>
                                                    @can('Detail Program Studi')
                                                        <a href="{{ route('program-studi.show', $prodi->id) }}"
                                                            class="btn btn-sm btn-primary me-2">
                                                            <i class="ti ti-info-circle"></i>
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

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Evaluasi</h5>
                        </div>
                        <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                            <table id="perkara" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%">
                                @can('Create Evaluasi Perguruan Tinggi')
                                    <a href="{{ route('evaluasi-organisasipt.create', $organisasi->id) }}"
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
                                                @can('View Detail Evaluasi Perguruan Tinggi')
                                                    <a href="{{ route('evaluasi-organisasipt.show', $perkara->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        <i class="ti ti-info-circle"></i>
                                                    </a>
                                                @endCan
                                                @can('Update Status Evaluasi Perguruan Tinggi')
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
                <a href="{{ route('perguruan-tinggi.index') }}" class="btn btn-sm btn-primary float-end me-2 mt-3">
                    Kembali
                </a>
            </section>
        </div>

        @include('Modal.Bp.Edit')
        @include('Akreditasi.PerguruanTinggi.Detail')
        @include('Pimpinan.PerguruanTinggi.Detail')
        @include('SK.PerguruanTinggi.Detail')
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#organisasi_table').DataTable();

            $('#akreditasi_table').DataTable();

            $('#sk_table').DataTable();

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#program_studi').DataTable();

            $('#akreditasi_program_studi').DataTable();

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

                document.getElementById('editStatusForm').action =
                    `/evaluasi-organisasipt/${perkaraId}/status-update`;
            }
        });
    </script>

    <script>
        var hasAkreditasiDokumenPermission = @json(auth()->user()->can('View PDF Akreditasi Perguruan Tinggi'));
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('akreditasi-detail')) {
                var akreditasiId = event.target.getAttribute('data-id');
                fetch('{{ route('akreditasi-perguruan-tinggi.getAkreditasiDetail', ':id') }}'.replace(":id",
                        akreditasiId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('org_nama').textContent = data.organisasi_nama;
                        document.getElementById('lembaga_nama').textContent = data
                            .lembaga_nama;
                        document.getElementById('peringkat_nama').textContent = data
                            .peringkat_nama;
                        document.getElementById('akreditasi_sk').textContent = data.akreditasi_sk;
                        document.getElementById('akreditasi_status').textContent = data.akreditasi_status;
                        document.getElementById('akreditasi_tgl_awal').textContent = data.akreditasi_tgl_awal;
                        document.getElementById('akreditasi_tgl_akhir').textContent = data.akreditasi_tgl_akhir;
                        if (hasAkreditasiDokumenPermission) {
                            if (data.akreditasi_dokumen) {
                                document.getElementById('btn_pdf').hidden = false;
                                document.getElementById('akreditasi_dokumen').value = data.akreditasi_dokumen;
                            } else {
                                document.getElementById('btn_pdf').hidden = true;
                            }
                        } else {
                            if (data.akreditasi_dokumen) {
                                document.getElementById('btn_pdf').hidden = false;
                                document.getElementById('akreditasi_dokumen').value = data.akreditasi_dokumen;
                            } else {
                                document.getElementById('btn_pdf').hidden = true;
                            }
                            document.getElementById('akreditasi_dokumen').value = 'Access Denied';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            if (event.target.classList.contains('pimpinan-detail')) {
                var pimpinanId = event.target.getAttribute('data-id');
                fetch('{{ route('pimpinan-perguruan-tinggi.show', ':id') }}'.replace(":id", pimpinanId))
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
                            document.getElementById('btn_pdf').hidden = false;
                            document.getElementById('pimpinan_sk_dokumen').value = data.pimpinan_sk_dokumen;
                        } else {
                            document.getElementById('btn_pdf').hidden = true;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            if (event.target.classList.contains('sk-detail')) {
                var skId = event.target.getAttribute('data-id');
                fetch('{{ route('sk-perguruan-tinggi.show', ':id') }}'.replace(":id", skId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('sk_nomor').textContent = data.sk_nomor;
                        document.getElementById('sk_tanggal').textContent = data.sk_tanggal;
                        document.getElementById('jsk_nama').textContent = data.jsk_nama;
                        document.getElementById('sk_deskripsi').textContent = data.sk_deskripsi;
                        if (data.sk_dokumen) {
                            document.getElementById('btn_pdf_sk').hidden = false;
                            document.getElementById('sk_dokumen').value = data.sk_dokumen;
                        } else {
                            document.getElementById('btn_pdf_sk').hidden = true;
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
            if ($.fn.DataTable.isDataTable('#akreditasi_program_studi')) {
                $('#akreditasi_program_studi').DataTable().destroy();
            }

            $('#akreditasi_program_studi').DataTable({
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
