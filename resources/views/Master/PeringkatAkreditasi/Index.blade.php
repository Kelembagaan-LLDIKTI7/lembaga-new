@extends('Layouts.Main')

@section('title', 'Peringkat Akreditasi')

@section('css')
    <style>
        #preview_container {
            position: relative;
            display: inline-block;
            width: fit-content;
        }

        #logo_preview {
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: block;
            position: relative;
        }

        #remove_preview {
            position: absolute;
            top: 0px;
            right: 0px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            z-index: 10;
            transform: translate(50%, -50%);
        }

        #edit_preview_container {
            position: relative;
            display: inline-block;
            width: fit-content;
        }

        #edit_logo_preview {
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: block;
            position: relative;
        }

        #remove_edit_preview {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            z-index: 10;
            transform: translate(50%, -50%);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Peringkat Akreditasi</h5>
                                @can('Create Peringkat Akreditasi')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createPeringkatModal">
                                        Tambah Peringkat
                                    </button>
                                @endCan
                            </div>

                            <div class="modal fade" id="createPeringkatModal" tabindex="-1"
                                aria-labelledby="createPeringkatModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createPeringkatModalLabel">
                                                Tambah Peringkat Akreditasi
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('peringkat-akademik.store') }}" method="POST"
                                                enctype="multipart/form-data" id="createPeringkatForm">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="peringkat_nama" class="required-label">
                                                        Nama Peringkat
                                                    </label>
                                                    <input type="text" class="form-control" id="peringkat_nama"
                                                        name="peringkat_nama" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="peringkat_logo" class="required-label">Logo</label>
                                                    <input type="file" class="form-control" id="peringkat_logo"
                                                        name="peringkat_logo" accept="image/*"
                                                        onchange="previewImage(event)" required>
                                                    <div id="preview_container" class="mt-3"
                                                        style="position: relative; display: none;">
                                                        <img id="logo_preview" src="" alt="Preview Logo"
                                                            style="max-height: 150px; border: 1px solid #ddd;">
                                                        <button type="button" id="remove_preview" onclick="removePreview()"
                                                            style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                                            &times;
                                                        </button>
                                                    </div>
                                                </div>

                                                <button type="submit" id="submit_button" class="btn btn-primary">
                                                    Simpan
                                                </button>
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
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#editPeringkatModal"
                                                            data-id="{{ $peringkat->id }}"
                                                            data-nama="{{ $peringkat->peringkat_nama }}"
                                                            data-status="{{ $peringkat->peringkat_status }}"
                                                            data-logo="{{ $peringkat->peringkat_logo }}">
                                                            Edit
                                                        </button>
                                                    @endCan

                                                    @can('Delete Peringkat Akreditasi')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#deletePeringkatModal"
                                                            data-id="{{ $peringkat->id }}"
                                                            data-nama="{{ $peringkat->peringkat_nama }}">
                                                            Hapus
                                                        </button>
                                                    @endCan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade" id="editPeringkatModal" tabindex="-1"
                                aria-labelledby="editLembagaModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editLembagaModalLabel">Edit Peringkat Akreditasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST" enctype="multipart/form-data"
                                                id="editPeringkatForm">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_nama" class="required-label">
                                                        Nama Peringkat
                                                    </label>
                                                    <input type="text" class="form-control" id="edit_peringkat_nama"
                                                        name="peringkat_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_status" class="required-label">Status</label>
                                                    <select class="form-select" id="edit_peringkat_status"
                                                        name="peringkat_status" required>
                                                        <option value="Aktif">Aktif</option>
                                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_peringkat_logo" class="form-label">Logo</label>
                                                    <input type="file" class="form-control" id="edit_peringkat_logo"
                                                        name="peringkat_logo" accept="image/*"
                                                        onchange="previewEditImage(event)">
                                                    <small class="text-muted d-block mt-1">Jika tidak diunggah, akan
                                                        menggunakan logo lama.</small>
                                                    <div id="edit_preview_container" class="mt-3"
                                                        style="position: relative; display: none;">
                                                        <img id="edit_logo_preview" src="" alt="Preview Logo"
                                                            style="max-height: 150px; border: 1px solid #ddd;">
                                                        <button type="button" id="remove_edit_preview"
                                                            onclick="removeEditPreview()"
                                                            style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                                            &times;
                                                        </button>
                                                    </div>
                                                    <span class="d-block mt-2" id="current_logo_info"></span>
                                                </div>
                                                <button type="submit" id="edit_submit_button"
                                                    class="btn btn-warning">Simpan Perubahan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="deletePeringkatModal" tabindex="-1"
                                aria-labelledby="deleteLembagaModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h5 class="modal-title" id="deleteLembagaModalLabel">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus peringkat <strong
                                                    id="peringkatNamaToDelete"></strong>? Tindakan ini tidak dapat
                                                dibatalkan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <form id="deleteLembagaForm" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
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
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#createPeringkatForm');
            const submitButton = document.getElementById('submit_button');

            form.addEventListener('submit', function(event) {
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...';
            });
        });

        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('preview_container');
            const preview = document.getElementById('logo_preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };

                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan.');
                    input.value = '';
                    return;
                }

                reader.readAsDataURL(file);
            }
        }

        function removePreview() {
            const input = document.getElementById('peringkat_logo');
            const previewContainer = document.getElementById('preview_container');
            const preview = document.getElementById('logo_preview');

            input.value = '';
            preview.src = '';
            previewContainer.style.display = 'none';
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editPeringkatModal');
            const editForm = document.getElementById('editPeringkatForm');
            const editNamaInput = document.getElementById('edit_peringkat_nama');
            const editStatusInput = document.getElementById('edit_peringkat_status');
            const editLogoPreview = document.getElementById('edit_logo_preview');
            const editPreviewContainer = document.getElementById('edit_preview_container');
            const currentLogoInfo = document.getElementById('current_logo_info');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const peringkatId = button.getAttribute('data-id');
                const peringkatNama = button.getAttribute('data-nama');
                const peringkatStatus = button.getAttribute('data-status');
                const peringkatLogo = button.getAttribute('data-logo');

                editForm.action = `{{ url('peringkat-akademik') }}/${peringkatId}`;
                editNamaInput.value = peringkatNama;
                editStatusInput.value = peringkatStatus;

                if (peringkatLogo) {
                    editLogoPreview.src = `{{ asset('storage/peringkat_akreditasi') }}/${peringkatLogo}`;
                    currentLogoInfo.textContent = `Logo saat ini: ${peringkatLogo}`;
                    editPreviewContainer.style.display = 'block';
                } else {
                    editPreviewContainer.style.display = 'none';
                    currentLogoInfo.textContent = 'Tidak ada logo saat ini.';
                }
            });
        });

        function previewEditImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('edit_preview_container');
            const preview = document.getElementById('edit_logo_preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };

                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan.');
                    input.value = '';
                    return;
                }

                reader.readAsDataURL(file);
            }
        }

        function removeEditPreview() {
            const input = document.getElementById('edit_lembaga_logo');
            const previewContainer = document.getElementById('edit_preview_container');
            const preview = document.getElementById('edit_logo_preview');

            input.value = '';
            preview.src = '';
            previewContainer.style.display = 'none';
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deletePeringkatModal');
            const peringkatNamaToDelete = document.getElementById('peringkatNamaToDelete');
            const deleteForm = document.getElementById('deleteLembagaForm');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const lembagaId = button.getAttribute('data-id');
                const lembagaNama = button.getAttribute('data-nama');

                peringkatNamaToDelete.textContent = lembagaNama;
                deleteForm.action = `{{ url('peringkat-akademik') }}/${lembagaId}`;
            });
        });
    </script>
@endsection
