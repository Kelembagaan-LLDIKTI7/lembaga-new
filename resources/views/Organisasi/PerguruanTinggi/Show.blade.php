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
                                <a href="{{ route('perguruan-tinggi.edit', $organisasi->id) }}" class="btn btn-warning">
                                    Edit
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

            @can('View Akreditasi Perguruan Tinggi')
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
                                            <th>Berlaku</th>
                                            <th>Lembaga Akreditasi</th>
                                            <th>Peringkat Akreditasi</th>
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
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $akre->akreditasi_sk }}</td>
                                                <td>{{ $akre->akreditasi_tgl_akhir }}</td>
                                                <td>{{ $akre->lembaga_nama_singkat }}</td>
                                                <td>{{ $akre->peringkat_nama }}</td>
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
                            <div class="table-responsive">
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
                                                <td>{{ $loop->iteration }}</td>
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
                            <div class="table-responsive">
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
                                                <td>{{ $loop->iteration }}</td>
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
                            <div class="table-responsive">
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
                                            <th>Nama Prodi</th>
                                            <th>Program</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organisasi->prodis as $prodi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $prodi->prodi_nama }}</td>
                                                <td>{{ $prodi->prodi_jenjang }}</td>
                                                <td>{{ $prodi->prodi_active_status }}</td>
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

            @can('View Akreditasi Program Studi')
                <section class="datatables">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Akreditasi Program Studi</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="akreditasi_program_studi"
                                    class="table-striped table-bordered display text-nowrap table border" style="width: 100%">
                                    @can('Create Program Studi')
                                        <a href="{{ route('program-studi.create', $organisasi->id) }}"
                                            class="btn btn-primary btn-sm mb-2">
                                            Tambah Program Studi
                                        </a>
                                    @endCan
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">No</th>
                                            <th colspan="2" class="text-center align-middle">Program Studi</th>
                                            <th colspan="4" class="text-center align-middle">SK Pimpinan</th>
                                            <th rowspan="2" class="text-center align-middle">Status</th>
                                            <th rowspan="2" class="text-center align-middle">Aksi</th>
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
                                        @foreach ($akreditasisProdi as $akreditasi)
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
                                                <td>
                                                    @can('Detail Program Studi')
                                                        <a href="{{ route('program-studi.show', $akreditasi->prodi->id) }}"
                                                            class="btn btn-sm btn-primary me-2">
                                                            <i class="ti ti-info-circle"></i>
                                                        </a>
                                                    @endCan
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- @foreach ($organisasi->prodis as $prodi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $prodi->prodi_nama }}</td>
                                                <td>{{ $prodi->prodi_jenjang }}</td>
                                                <td>{{ $prodi->prodi_active_status }}</td>
                                                
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('perguruan-tinggi.index') }}" class="btn btn-sm btn-primary float-end me-2 mt-3">
                        Kembali
                    </a>
                </section>
            @endCan
        </div>

        @include('Akreditasi.PerguruanTinggi.Detail')
        @include('Pimpinan.PerguruanTinggi.Detail')
        @include('SK.PerguruanTinggi.Detail')
    </div>

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
        });
    </script>

    <script>
        var hasAkreditasiDokumenPermission = @json(auth()->user()->can('View PDF Akreditasi Perguruan Tinggi'));
        // Event listener untuk tombol detail
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('akreditasi-detail')) {
                var akreditasiId = event.target.getAttribute('data-id');
                fetch('{{ route('akreditasi-perguruan-tinggi.getAkreditasiDetail', ':id') }}'.replace(":id",
                        akreditasiId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('org_nama').textContent = data.organisasi_nama;
                        document.getElementById('prodi_nama').textContent = data.prodi_nama;
                        document.getElementById('lembaga_nama').textContent = data
                            .lembaga_nama;
                        document.getElementById('peringkat_nama').textContent = data
                            .peringkat_nama;
                        document.getElementById('akreditasi_sk').textContent = data.akreditasi_sk;
                        document.getElementById('akreditasi_status').textContent = data.akreditasi_status;
                        document.getElementById('akreditasi_tgl_awal').textContent = data.akreditasi_tgl_awal;
                        document.getElementById('akreditasi_tgl_akhir').textContent = data.akreditasi_tgl_akhir;
                        if (hasAkreditasiDokumenPermission) {
                            document.getElementById('akreditasi_dokumen').value = data.akreditasi_dokumen;
                        } else {
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
                        document.getElementById('pimpinan_status').textContent = data.pimpinan_status;
                        document.getElementById('pimpinan_sk').textContent = data.pimpinan_sk;
                        document.getElementById('pimpinan_sk_dokumen').value = data.pimpinan_sk_dokumen;
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
                        document.getElementById('sk_berakhir').textContent = data.sk_berakhir;
                        document.getElementById('jsk_nama').textContent = data.jsk_nama;
                        document.getElementById('sk_dokumen').value = data.sk_dokumen;
                    })
            }
        });
    </script>

@endsection
