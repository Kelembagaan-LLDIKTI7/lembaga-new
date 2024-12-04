@extends('Layouts.Main')

@section('title', 'Jenis Organisasi')

@section('content')
    <div class="container-fluid">
        <h4 class="title mb-4">Jenis Organisasi</h4>
        <div class="d-flex justify-content-end mb-3">
            @can('Create Jenis Organisasi')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrganisasiTypeModal">
                    Tambah Jenis Organisasi
                </button>
            @endCan
        </div>
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card bordered">
                        <div class="card-body">

                            <!-- Modal for Creating Organisasi Type -->
                            <div class="modal fade" id="createOrganisasiTypeModal" tabindex="-1"
                                aria-labelledby="createOrganisasiTypeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createOrganisasiTypeModalLabel">Tambah Jenis
                                                Organisasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('organisasi-type.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="organisasi_type_nama" class="form-label">Nama
                                                        Organisasi</label>
                                                    <input type="text" class="form-control" id="organisasi_type_nama"
                                                        name="organisasi_type_nama" required>
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
                                            <th>Nama Organisasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organisasiTypes as $organisasiType)
                                            <tr>
                                                <td></td>
                                                <td>{{ $organisasiType->organisasi_type_nama }}</td>
                                                <td>
                                                    @can('Edit Jenis Organisasi')
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#editOrganisasiTypeModal"
                                                            data-id="{{ $organisasiType->id }}"
                                                            data-nama="{{ $organisasiType->organisasi_type_nama }}">
                                                            Edit
                                                        </button>
                                                    @endCan
                                                    @can('Delete Jenis Organisasi')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#deleteOrganisasiTypeModal"
                                                            data-id="{{ $organisasiType->id }}"
                                                            data-nama="{{ $organisasiType->organisasi_type_nama }}">
                                                            Hapus
                                                        </button>
                                                    @endCan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal for Editing Organisasi Type -->
                            <div class="modal fade" id="editOrganisasiTypeModal" tabindex="-1"
                                aria-labelledby="editOrganisasiTypeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editOrganisasiTypeModalLabel">Edit Jenis Organisasi
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editOrganisasiTypeForm" action="" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_organisasi_type_id" name="id">
                                                <div class="mb-3">
                                                    <label for="edit_organisasi_type_nama" class="form-label">Nama
                                                        Organisasi</label>
                                                    <input type="text" class="form-control"
                                                        id="edit_organisasi_type_nama" name="organisasi_type_nama" required>
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

                            <!-- Modal for Deleting Organisasi Type -->
                            <div class="modal fade" id="deleteOrganisasiTypeModal" tabindex="-1"
                                aria-labelledby="deleteOrganisasiTypeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h5 class="modal-title" id="deleteOrganisasiTypeModalLabel">Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus jenis organisasi <strong
                                                    id="organisasiTypeNamaToDelete"></strong>? Tindakan ini tidak dapat
                                                dibatalkan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form id="deleteOrganisasiTypeForm" method="POST">
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

    <script>
        const editOrganisasiTypeModal = document.getElementById('editOrganisasiTypeModal');
        editOrganisasiTypeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const organisasiTypeId = button.getAttribute('data-id');
            const organisasiTypeNama = button.getAttribute('data-nama');

            // Fill the modal with the data
            const form = document.getElementById('editOrganisasiTypeForm');
            form.action = `/organisasi-type/${organisasiTypeId}`; // Set form action to the update route
            document.getElementById('edit_organisasi_type_id').value = organisasiTypeId;
            document.getElementById('edit_organisasi_type_nama').value = organisasiTypeNama;
        });

        const deleteOrganisasiTypeModal = document.getElementById('deleteOrganisasiTypeModal');
        const deleteOrganisasiTypeForm = document.getElementById('deleteOrganisasiTypeForm');
        const organisasiTypeNamaToDelete = document.getElementById('organisasiTypeNamaToDelete');

        deleteOrganisasiTypeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const organisasiTypeId = button.getAttribute('data-id');
            const organisasiTypeNama = button.getAttribute('data-nama');

            // Fill the modal with the data
            organisasiTypeNamaToDelete.textContent = organisasiTypeNama;
            deleteOrganisasiTypeForm.action =
                `/organisasi-type/${organisasiTypeId}`; // Set form action to the delete route
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
@endsection
