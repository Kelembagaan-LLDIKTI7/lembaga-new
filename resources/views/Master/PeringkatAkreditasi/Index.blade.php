@extends('Layouts.Main')

@section('title', 'Peringkat Akreditasi')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Peringkat Akreditasi</h5>
                                @can('Create Peringkat Akreditasi')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPeringkatModal">
                                    Tambah Peringkat
                                </button>
                                @endCan
                            </div>

                            <!-- Modal for Create Peringkat -->
                            <div class="modal fade" id="createPeringkatModal" tabindex="-1" aria-labelledby="createPeringkatModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createPeringkatModalLabel">Tambah Peringkat Akreditasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('peringkat-akademik.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="peringkat_nama" class="form-label">Nama Peringkat</label>
                                                    <input type="text" class="form-control" id="peringkat_nama" name="peringkat_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="peringkat_logo" class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="peringkat_logo" name="peringkat_logo">
                                                </div>
                                                <input type="hidden" name="peringkat_status" value="Aktif">
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
                                            <th>Peringkat</th>
                                            <th>Logo</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peringkats as $peringkat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $peringkat->peringkat_nama }}</td>
                                                <td>
                                                    @if ($peringkat->peringkat_logo)
                                                        <img src="{{ asset('storage/peringkat_akreditasi/' . $peringkat->peringkat_logo) }}"
                                                            alt="Logo" width="50" height="50">
                                                    @else
                                                        <span>No Logo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $peringkat->peringkat_status }}</td>
                                                <td>
                                                    @can('Edit Peringkat Akreditasi')
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPeringkatModal"
                                                        data-id="{{ $peringkat->id }}"
                                                        data-nama="{{ $peringkat->peringkat_nama }}"
                                                        data-status="{{ $peringkat->peringkat_status }}">
                                                        Edit
                                                    </button>
                                                    @endCan
                                                    <!-- Delete Button -->
                                                     @can('Delete Peringkat Akreditasi')
                                                    <form action="{{ route('peringkat-akademik.destroy', $peringkat->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Peringkat?');">
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

                            <!-- Modal for Edit Peringkat -->
                            <div class="modal fade" id="editPeringkatModal" tabindex="-1" aria-labelledby="editPeringkatModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPeringkatModalLabel">Edit Peringkat Akreditasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editPeringkatForm" action="" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="edit_peringkat_id" name="id">
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_nama" class="form-label">Nama Peringkat</label>
                                                    <input type="text" class="form-control" id="edit_peringkat_nama" name="peringkat_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_logo" class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="edit_peringkat_logo" name="peringkat_logo">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_status" class="form-label">Status</label>
                                                    <select class="form-select" id="edit_peringkat_status" name="peringkat_status" required>
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
        const editPeringkatModal = document.getElementById('editPeringkatModal');
        editPeringkatModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const peringkatId = button.getAttribute('data-id');
            const peringkatNama = button.getAttribute('data-nama');
            const peringkatStatus = button.getAttribute('data-status');

            // Fill the modal with data
            const form = document.getElementById('editPeringkatForm');
            form.action = `/peringkat-akademik/${peringkatId}`; // Set form action to update route
            document.getElementById('edit_peringkat_id').value = peringkatId;
            document.getElementById('edit_peringkat_nama').value = peringkatNama;
            document.getElementById('edit_peringkat_status').value = peringkatStatus; // Set the status in the dropdown
        });
    </script>
@endsection
