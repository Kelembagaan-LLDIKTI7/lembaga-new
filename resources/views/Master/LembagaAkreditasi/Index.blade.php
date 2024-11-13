@extends('Layouts.Main')

@section('title', 'Lembaga Akreditasi')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Lembaga Akreditasi</h5>
                                @can('Create Lembaga Akreditasi')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLembagaModal">
                                    Tambah Lembaga
                                </button>
                                @endCan
                            </div>

                            <!-- Modal for Create Lembaga -->
                            <div class="modal fade" id="createLembagaModal" tabindex="-1" aria-labelledby="createLembagaModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createLembagaModalLabel">Tambah Lembaga Akreditasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('lembaga-akademik.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="lembaga_nama" class="form-label">Nama Lembaga</label>
                                                    <input type="text" class="form-control" id="lembaga_nama" name="lembaga_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="lembaga_nama_singkat" class="form-label">Akronim</label>
                                                    <input type="text" class="form-control" id="lembaga_nama_singkat" name="lembaga_nama_singkat" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="lembaga_logo" class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="lembaga_logo" name="lembaga_logo">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="lembaga_status" class="form-label">Status</label>
                                                    <select class="form-control" id="lembaga_status" name="lembaga_status" required>
                                                        <option value="aktif">Aktif</option>
                                                        <option value="tidak aktif">Tidak Aktif</option>
                                                    </select>
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
                                            <th>Nama Lembaga</th>
                                            <th>Akronim</th>
                                            <th>Logo</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lembagas as $lembaga)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $lembaga->lembaga_nama }}</td>
                                                <td>{{ $lembaga->lembaga_nama_singkat }}</td>
                                                <td>
                                                    @if ($lembaga->lembaga_logo)
                                                        <img src="{{ asset('storage/lembaga_akreditasi/' . $lembaga->lembaga_logo) }}" alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $lembaga->lembaga_status }}</td>
                                                <td>
                                                    @can('Edit Lembaga Akreditasi')
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editLembagaModal"
                                                        data-id="{{ $lembaga->id }}"
                                                        data-nama="{{ $lembaga->lembaga_nama }}"
                                                        data-akronim="{{ $lembaga->lembaga_nama_singkat }}"
                                                        data-status="{{ $lembaga->lembaga_status }}">
                                                        Edit
                                                    </button>
                                                    @endCan
                                                    <!-- Delete Button -->
                                                     @can('Delete Lembaga Akreditasi')
                                                    <form action="{{ route('lembaga-akademik.destroy', $lembaga->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Lembaga?');">
                                                            Delete
                                                        </button>
                                                    </form>
                                                    @endCan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal for Edit Lembaga -->
                            <div class="modal fade" id="editLembagaModal" tabindex="-1" aria-labelledby="editLembagaModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editLembagaModalLabel">Edit Lembaga Akreditasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editLembagaForm" action="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_lembaga_id" name="id">
                                                <div class="mb-3">
                                                    <label for="edit_lembaga_nama" class="form-label">Nama Lembaga</label>
                                                    <input type="text" class="form-control" id="edit_lembaga_nama" name="lembaga_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_lembaga_nama_singkat" class="form-label">Akronim</label>
                                                    <input type="text" class="form-control" id="edit_lembaga_nama_singkat" name="lembaga_nama_singkat" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_lembaga_logo" class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="edit_lembaga_logo" name="lembaga_logo">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_lembaga_status" class="form-label">Status</label>
                                                    <select class="form-control" id="edit_lembaga_status" name="lembaga_status" required>
                                                        <option value="aktif">Aktif</option>
                                                        <option value="tidak aktif">Tidak Aktif</option>
                                                    </select>
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
        const editLembagaModal = document.getElementById('editLembagaModal');
        editLembagaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const lembagaId = button.getAttribute('data-id');
            const lembagaNama = button.getAttribute('data-nama');
            const lembagaAkronim = button.getAttribute('data-akronim');
            const lembagaStatus = button.getAttribute('data-status');

            // Fill the modal with data
            const form = document.getElementById('editLembagaForm');
            form.action = `/lembaga-akademik/${lembagaId}`; // Set form action to update route
            document.getElementById('edit_lembaga_id').value = lembagaId;
            document.getElementById('edit_lembaga_nama').value = lembagaNama;
            document.getElementById('edit_lembaga_nama_singkat').value = lembagaAkronim;
            document.getElementById('edit_lembaga_status').value = lembagaStatus; // Set the status in the dropdown
        });
    </script>
@endsection
