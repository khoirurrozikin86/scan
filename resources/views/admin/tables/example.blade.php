@extends('layouts.admin')
@section('title', 'Roles')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Access</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">Roles</h6>
                        <a href="{{ route('super.roles.create') }}" class="btn btn-primary btn-sm">
                            + New
                        </a>
                    </div>

                    {{-- alert success (opsional) --}}
                    @if (session('ok'))
                        <div class="alert alert-success">{{ session('ok') }}</div>
                    @endif



                    <div class="table-responsive">
                        <table id="roles-table" class="table w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Users</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            {{-- DataTables (serverSide) akan mengisi tbody --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-styles')
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
@endpush

@push('vendor-scripts')
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dt = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: @json(route('super.roles.dt')),
                    data: function(d) {
                        d.custom_search = $('#role-search').val(); // ikutkan filter custom (opsional)
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'users_count',
                        name: 'users_count',
                        searchable: false
                    },
                    {
                        data: 'permissions_count',
                        name: 'permissions_count',
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'asc']
                ]
            });

            // global search bawaan DT
            $('#role-search').on('keyup', function() {
                dt.search(this.value).draw();
            });

            // tombol filter manual (pakai ajax.data custom di atas)
            $('#role-search-btn').on('click', function(e) {
                e.preventDefault();
                dt.draw();
            });
        });

        $('#roles-table').on('draw.dt', function() {
            if (window.feather) feather.replace();
        });
    </script>
@endpush
