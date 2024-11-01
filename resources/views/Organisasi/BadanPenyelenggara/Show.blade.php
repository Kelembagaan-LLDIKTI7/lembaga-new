@extends('Layouts.Main')

@section('title', 'Detail Badan Penyelenggara')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">test</h5>
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
                        <a href="" class="btn btn-primary me-2">Alih Bentuk</a>
                        <a href="" class="btn btn-warning">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Pimpinan Badan Penyelenggara Yang dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="pimpinan_table" class="table-striped table-bordered display text-nowrap table border"
                                style="overflow-x: auto; overflow-y: hidden;">
                                <a href="{{ route('pimpinan-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                    class="btn btn-primary btn-sm mb-2">
                                    Tambah Pimpinan BP
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pimpinan as $pimpinan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pimpinan->pimpinan_nama }}</td>
                                            <td>{{ $pimpinan->pimpinan_email }}</td>
                                            <td>{{ $pimpinan->pimpinan_status }}</td>
                                            <td>{{ $pimpinan->jabatan->jabatan_nama }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ route('pimpinan-badan-penyelenggara.edit', ['id' => $pimpinan->id]) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="ri-edit-2-line"></i> Edit
                                                    </a>
                                                    <button class="btn btn-info btn-sm pimpinan-detail"
                                                        data-bs-toggle="modal" data-bs-target="#detailRecordModalPimpinan"
                                                        data-id="{{ $pimpinan->id }}">
                                                        Detail
                                                    </button>
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

            <section class="datatables">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5 class="mb-0">Perguruan Tinggi Yang Dimiliki</h5>
                        </div>
                        <div class="table-responsive">
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bp->organisasi_nama }}</td>
                                            <td>{{ $bp->organisasi_nama_singkat }}</td>
                                            <td>{{ $bp->organisasi_kota }}</td>
                                            <td>{{ $bp->organisasi_status }}</td>
                                            <td>
                                                <a href="{{ route('perguruan-tinggi.show', $bp->id) }}"
                                                    class="btn btn-sm btn-primary me-2">
                                                    <i class="ti ti-info-circle"></i>
                                                </a>
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
                            <h5 class="mb-0">Akta Yang Dimiliki</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="program_studi" class="table-striped table-bordered display text-nowrap table border"
                                style="width: 100%">
                                <a href="{{ route('akta-badan-penyelenggara.create', $badanPenyelenggaras->id) }}"
                                    class="btn btn-primary btn-sm mb-2">
                                    Tambah Akta
                                </a>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Akta</th>
                                        <th>Nomor Tanggal</th>
                                        <th>Status Akta</th>
                                        <th>Nomor SK Kumham</th>
                                        <th>Tanggal SK Kuham</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($akta as $akta)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $akta->akta_nomor }}</td>
                                            <td>{{ $akta->akta_tanggal }}</td>
                                            <td>{{ $akta->akta_status }}</td>
                                            <td>{{ optional($akta->skKumham)->kumham_nomor ?? 'N/A' }}</td>
                                            <td>{{ optional($akta->skKumham)->kumham_tanggal ?? 'N/A' }}</td>
                                            <td>
                                                <div class="edit">
                                                    <a href="{{ route('akta-badan-penyelenggara.edit', $akta->id) }}"
                                                        class="btn btn-sm btn-success mb-2">Edit</a>
                                                </div>
                                                <div class="kumham">
                                                    <a href="{{ route('sk-kumham.create', $akta->id) }}"
                                                        class="btn btn-sm btn-warning mb-2">
                                                        SK Kumham
                                                    </a>
                                                </div>
                                                <button class="btn btn-info btn-sm mb-2 akta-detail" data-bs-toggle="modal"
                                                    data-bs-target="#detailRecordModalAkta" data-id="{{ $akta->id }}">
                                                    Detail
                                                </button>
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

            $('#pemimpin_perguruan_tinggi').DataTable();

            $('#program_studi').DataTable();
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
                        document.getElementById('pimpinan_status').textContent = data.pimpinan_status;
                        document.getElementById('pimpinan_sk').textContent = data.pimpinan_sk;
                        document.getElementById('pimpinan_sk_dokumen').value = data.pimpinan_sk_dokumen;
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
                        document.getElementById('akta_status').textContent = data.akta_status;
                        document.getElementById('akta_dokumen').value = data.akta_dokumen;
                        document.getElementById('kumham_nomor').textContent = data.kumham_nomor ??
                            '-';
                        document.getElementById('kumham_perihal').textContent = data.kumham_perihal ??
                            '-';
                        document.getElementById('kumham_tanggal').textContent = data.kumham_tanggal ??
                            '-';
                        document.getElementById('kumham_dokumen').value = data.kumham_dokumen ??
                            '';
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

@endsection
