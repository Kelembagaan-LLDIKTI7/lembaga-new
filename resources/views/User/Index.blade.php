@extends('Layouts.Main')

@section('title', 'User')

@section('content')
    <section class="datatables">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">User</h5>
                        @can('Create User')
                                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm mb-2">
                                    Tambah User
                                </a>
                        @endCan
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
                        <table id="dom_jq_event" class="table-striped table-bordered display text-nowrap table border"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Organisasi</th>
                                    @canAny(['Edit User', 'Delete User'])
                                        <th>Aksi</th>
                                    @endCanAny
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if (!empty($item->getRoleNames()))
                                                @foreach ($item->getRoleNames() as $rolename)
                                                    <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->is_active == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $item->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->organization->organisasi_nama }}</td>
                                        @canAny(['Edit User', 'Delete User'])
                                            <td>
                                                @can('Edit User')
                                                    @if (!$item->hasRole('Super Admin'))
                                                        <a href="{{ route('user.edit', $item->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="ri-edit-2-line"></i> Edit
                                                        </a>
                                                    @endif
                                                @endcan
                                                @can('Delete User')
                                                    @if (!$item->hasRole('Super Admin'))
                                                        <a href="#" class="btn btn-sm btn-danger"
                                                            onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                                                            <i class="ri-delete-bin-line"></i> Delete
                                                        </a>
                                                        <form id="delete-form-{{ $item->id }}"
                                                            action="{{ route('user.destroy', $item->id) }}" method="POST"
                                                            style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                @endcan
                                            </td>
                                        @endCanAny
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </section>
@endsection
