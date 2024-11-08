@extends('Layouts.Main')

@section('title', 'Jabatan')

@section('content')
    <div class="container-fluid">
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Jabatan</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJabatanModal">
                                    Tambah Jabatan
                                </button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="createJabatanModal" tabindex="-1" aria-labelledby="createJabatanModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-end">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createJabatanModalLabel">Tambah Jabatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('jabatan.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="jabatan_nama" class="form-label">Nama Jabatan</label>
                                                    <input type="text" class="form-control" id="jabatan_nama" name="jabatan_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bentuk_pt" class="form-label">Bentuk PT</label>
                                                    <select class="form-select" id="bentuk_pt" name="bentuk_pt" required>
                                                        @foreach($bentuk_pts as $id => $bentuk_nama)
                                                            <option value="{{ $id }}">{{ $bentuk_nama }}</option>
                                                        @endforeach
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
                                            <th>Nama Jabatan</th>
                                            <th>Organisasi</th>
                                            <th>Bentuk PT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jabatans as $jabatan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jabatan->jabatan_nama }}</td>
                                                <td>{{ $jabatan->jabatan_organisasi }}</td>
                                                <td>{{ $bentuk_pts[$jabatan->bentuk_pt] ?? 'Unknown' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
