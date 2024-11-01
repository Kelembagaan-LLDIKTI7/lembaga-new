@extends('layouts.Main')
@section('title', 'Role User')
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
                        <h4 class="mb-sm-0">Role User</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="route {{ 'dashboard.index' }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Role User</li>
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
                                <div>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                                        Tambah Role User
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="roleTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ route('addRolePermission', $item->id) }}" class="btn btn-sm btn-info">
                                                        <i class="ri-attachment-2"></i>Permission</a>

                                                    <a href="#" class="btn btn-sm btn-success btn-edit-role" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                        <i class="ri-edit-2-line"></i> Edit
                                                    </a>

                                                    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                                        <i class="ri-delete-bin-line"></i> Delete
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}" action="{{ route('roles.destroy', $item->id) }}" method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- Modal for both create and edit -->
         <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roleModalLabel">Form Role User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="roleForm" method="POST" class="row g-3">
                            @csrf
                            <input type="hidden" id="methodField">
                            <div class="row g-3">
                                <div class="col-xxl-12">
                                    <div>
                                        <label for="name" class="form-label">Nama Role</label>
                                        <input type="text" class="form-control" id="roleName" name="name" placeholder="Ex: Admin">
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
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
                    </script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#roleTable').DataTable({
            order: [
                [0, 'asc']
            ],
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
        });
    });

   // Handle Add Role button click
   $('.btn-add-role').click(function() {
            $('#roleModal').modal('show');
            $('#roleModalLabel').text('Tambah Role User');
            $('#roleForm').attr('action', '{{ route('roles.store') }}');
            $('#methodField').remove();
            $('#roleName').val(''); // Clear previous value
        });

    // Handle Edit Role button click
    $(document).on('click', '.btn-edit-role', function() {
            var roleId = $(this).data('id');
            var roleName = $(this).data('name');
            $('#roleModal').modal('show');
            $('#roleModalLabel').text('Edit Role User');
            $('#roleForm').attr('action', '/role/' + roleId);
            $('#roleForm').append('<input type="hidden" id="methodField" name="_method" value="PUT">');
            $('#roleName').val(roleName);
        });
</script>
@endsection