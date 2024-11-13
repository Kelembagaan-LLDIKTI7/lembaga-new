@extends('Layouts.Main')

@section('title', 'Jenis Organisasi')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Jenis Organisasi</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrganisasiTypeModal">
                                    Tambah Jenis Organisasi
                                </button>
                            </div>

                            <!-- Modal for Creating Organisasi Type -->
                            <div class="modal fade" id="createOrganisasiTypeModal" tabindex="-1" aria-labelledby="createOrganisasiTypeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createOrganisasiTypeModalLabel">Tambah Jenis Organisasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('organisasi-type.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="organisasi_type_nama" class="form-label">Nama Organisasi</label>
                                                    <input type="text" class="form-control" id="organisasi_type_nama" name="organisasi_type_nama" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
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
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $organisasiType->organisasi_type_nama }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOrganisasiTypeModal"
                                                        data-id="{{ $organisasiType->id }}"
                                                        data-nama="{{ $organisasiType->organisasi_type_nama }}">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('organisasi-type.destroy', $organisasiType->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Organisasi Type?');">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal for Editing Organisasi Type -->
                            <div class="modal fade" id="editOrganisasiTypeModal" tabindex="-1" aria-labelledby="editOrganisasiTypeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editOrganisasiTypeModalLabel">Edit Jenis Organisasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editOrganisasiTypeForm" action="" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_organisasi_type_id" name="id">
                                                <div class="mb-3">
                                                    <label for="edit_organisasi_type_nama" class="form-label">Nama Organisasi</label>
                                                    <input type="text" class="form-control" id="edit_organisasi_type_nama" name="organisasi_type_nama" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
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
        editOrganisasiTypeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const organisasiTypeId = button.getAttribute('data-id');
            const organisasiTypeNama = button.getAttribute('data-nama');

            // Fill the modal with the data
            const form = document.getElementById('editOrganisasiTypeForm');
            form.action = `/organisasi-type/${organisasiTypeId}`; // Set form action to the update route
            document.getElementById('edit_organisasi_type_id').value = organisasiTypeId;
            document.getElementById('edit_organisasi_type_nama').value = organisasiTypeNama;
        });
    </script>
@endsection
