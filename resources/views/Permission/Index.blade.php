@extends('Layouts.Main')
@section('title', 'Permission Role User')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endsection
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Permission Role User</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Permission Role User</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-4">
                                @can('Create Permission')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                    Tambah Permission Role User
                                </button>
                                @endCan
                            </div>
                            <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%" id="PermissionTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Permission</th>
                                            @canAny(['Edit Permission', 'Delete Permission'])
                                            <th>Actions</th>
                                            @endCanAny
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permission as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            @canAny(['Edit Permission', 'Delete Permission'])
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @can('Edit Permission')
                                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editPermissionModal-{{ $item->id }}">
                                                        <i class="ri-edit-2-line"></i> Edit
                                                    </button>
                                                    @endCan
                                                    @can('Delete Permission')
                                                    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                                        <i class="ri-delete-bin-line"></i> Delete
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}" action="{{ route('permission.destroy', $item->id) }}" method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endCan
                                                </div>
                                            </td>
                                            @endCanAny
                                        </tr>

                                        <!-- Edit Role Modal for each role -->
                                        <div class="modal fade" id="editPermissionModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editPermissionModalLabel-{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPermissionModalLabel-{{ $item->id }}">Edit Permission Role User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('permission.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label for="editPermissionName-{{ $item->id }}" class="form-label">Nama Permission</label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="editPermissionName-{{ $item->id }}" name="name" value="{{ old('name', $item->name) }}" required>
                                                                @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Role Modal -->
        <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPermissionModalLabel">Tambah Permission Role User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('permission.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="addPermissionName" class="form-label">Nama Role</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="addPermissionName" name="name" value="{{ old('name') }}" placeholder="Ex: Create User" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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