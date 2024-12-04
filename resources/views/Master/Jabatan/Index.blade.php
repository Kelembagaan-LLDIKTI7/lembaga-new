@extends('Layouts.Main')

@section('title', 'Jabatan')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4 title">Jabatan</h4>
        <div class="mb-3 d-flex justify-content-end align-items-center">
            @can('Create Jabatan')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJabatanModal">
                    Tambah Jabatan
                </button>
            @endCan
        </div>
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card bordered">
                        <div class="card-body">

                            <!-- Modal -->
                            <div class="modal fade" id="createJabatanModal" tabindex="-1"
                                aria-labelledby="createJabatanModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createJabatanModalLabel">Tambah Jabatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('jabatan.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="jabatan_nama" class="form-label">Nama Jabatan</label>
                                                    <input type="text" class="form-control" id="jabatan_nama"
                                                        name="jabatan_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bentuk_pt" class="form-label">Bentuk PT</label>
                                                    <select class="form-select" id="bentuk_pt" name="bentuk_pt" required>
                                                        @foreach ($bentuk_pts as $id => $bentuk_nama)
                                                            <option value="{{ $id }}">{{ $bentuk_nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jabatan_organisasi" class="form-label">Organisasi</label>
                                                    <select class="form-select" id="jabatan_organisasi"
                                                        name="jabatan_organisasi" required>
                                                        @foreach ($organisasi_types as $id => $organisasi_nama)
                                                            <option value="{{ $id }}">{{ $organisasi_nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="button-container">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jabatan</th>
                                            <th>Organisasi</th>
                                            <th>Bentuk PT</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jabatans as $jabatan)
                                            <tr>
                                                <td></td>
                                                <td>{{ $jabatan->jabatan_nama }}</td>
                                                <td>{{ $organisasi_types[$jabatan->jabatan_organisasi] ?? 'Unknown' }}</td>
                                                <td>{{ $bentuk_pts[$jabatan->bentuk_pt] ?? 'Unknown' }}</td>
                                                <td>
                                                    @can('Edit Jabatan')
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#editJabatanModal"
                                                            data-id="{{ $jabatan->id }}"
                                                            data-nama="{{ $jabatan->jabatan_nama }}"
                                                            data-bentuk="{{ $jabatan->bentuk_pt }}"
                                                            data-organisasi="{{ $jabatan->jabatan_organisasi }}">
                                                            Edit
                                                        </button>
                                                    @endCan
                                                    <!-- Delete Button -->
                                                    @can('Delete Jabatan')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#deleteJabatanModal"
                                                            data-id="{{ $jabatan->id }}"
                                                            data-nama="{{ $jabatan->jabatan_nama }}">
                                                            Hapus
                                                        </button>
                                                    @endCan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal untuk Edit Jabatan -->
                            <div class="modal fade" id="editJabatanModal" tabindex="-1"
                                aria-labelledby="editJabatanModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editJabatanModalLabel">Edit Jabatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editJabatanForm" action="" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_jabatan_id" name="id">
                                                <div class="mb-3">
                                                    <label for="edit_jabatan_nama" class="form-label">Nama Jabatan</label>
                                                    <input type="text" class="form-control" id="edit_jabatan_nama"
                                                        name="jabatan_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_bentuk_pt" class="form-label">Bentuk PT</label>
                                                    <select class="form-select" id="edit_bentuk_pt" name="bentuk_pt"
                                                        required>
                                                        @foreach ($bentuk_pts as $id => $bentuk_nama)
                                                            <option value="{{ $id }}">{{ $bentuk_nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_jabatan_organisasi"
                                                        class="form-label">Organisasi</label>
                                                    <select class="form-select" id="edit_jabatan_organisasi"
                                                        name="jabatan_organisasi" required>
                                                        @foreach ($organisasi_types as $id => $organisasi_nama)
                                                            <option value="{{ $id }}">{{ $organisasi_nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="button-container">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal untuk Hapus Jabatan -->
                            <div class="modal fade" id="deleteJabatanModal" tabindex="-1"
                                aria-labelledby="deleteJabatanModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h5 class="modal-title" id="deleteJabatanModalLabel">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus jabatan <strong
                                                    id="jabatanNamaToDelete"></strong>? Tindakan ini tidak dapat
                                                dibatalkan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form id="deleteJabatanForm" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Tambahkan skrip JavaScript di sini -->
    <script>
        const editJabatanModal = document.getElementById('editJabatanModal');
        editJabatanModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const jabatanId = button.getAttribute('data-id');
            const jabatanNama = button.getAttribute('data-nama');
            const bentukPt = button.getAttribute('data-bentuk');
            const jabatanOrganisasi = button.getAttribute('data-organisasi'); // Ambil data organisasi

            // Mengisi data ke dalam modal
            const form = document.getElementById('editJabatanForm');
            form.action = `/jabatan/${jabatanId}`; // Mengatur action form ke rute update
            document.getElementById('edit_jabatan_id').value = jabatanId;
            document.getElementById('edit_jabatan_nama').value = jabatanNama;
            document.getElementById('edit_bentuk_pt').value = bentukPt;
            document.getElementById('edit_jabatan_organisasi').value = jabatanOrganisasi; // Mengisi organisasi
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#dom_jq_event')) {
                $('#dom_jq_event').DataTable().destroy();
            }

            $('#dom_jq_event').DataTable({
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteJabatanModal');
            const jabatanNamaToDelete = document.getElementById('jabatanNamaToDelete');
            const deleteForm = document.getElementById('deleteJabatanForm');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const jabatanId = button.getAttribute('data-id');
                const jabatanNama = button.getAttribute('data-nama');

                jabatanNamaToDelete.textContent = jabatanNama;
                deleteForm.action = `{{ url('jabatan') }}/${jabatanId}`;
            });
        });
    </script>
@endsection
