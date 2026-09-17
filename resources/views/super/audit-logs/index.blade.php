@extends('layouts.admin')

@section('title', 'Audit Log')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h4 class="mb-1">
                    Audit Log
                </h4>

                <div class="text-muted">
                    Riwayat aktivitas dan perubahan sistem
                </div>
            </div>

        </div>


        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">

                <div class="row g-3 align-items-end">

                    {{-- Date From --}}
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">
                            Tanggal Dari
                        </label>

                        <input type="date" class="form-control" id="date_from" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">
                            Tanggal Sampai
                        </label>

                        <input type="date" class="form-control" id="date_to" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    {{-- Module --}}
                    <div class="col-md-2">
                        <label for="module" class="form-label">
                            Module
                        </label>

                        <select class="form-select" id="module">
                            <option value="">Semua Module</option>
                            <option value="AUTH">AUTH</option>
                            <option value="OUTLET">OUTLET</option>
                            <option value="USER">USER</option>
                            <option value="ROLE">ROLE</option>
                            <option value="PERMISSION">PERMISSION</option>
                            <option value="USER_OUTLET">USER OUTLET</option>
                            <option value="TICKET_QRCODE">TICKET QRCODE</option>
                            <option value="SCAN_RECORD">SCAN RECORD</option>
                            <option value="SETTING">SETTING</option>
                        </select>
                    </div>

                    {{-- Action --}}
                    <div class="col-md-2">
                        <label for="action" class="form-label">
                            Action
                        </label>

                        <select class="form-select" id="action">
                            <option value="">Semua Action</option>
                            <option value="CREATE">CREATE</option>
                            <option value="UPDATE">UPDATE</option>
                            <option value="DELETE">DELETE</option>
                            <option value="ASSIGN">ASSIGN</option>
                            <option value="UNASSIGN">UNASSIGN</option>
                            <option value="IMPORT">IMPORT</option>
                            <option value="EXPORT">EXPORT</option>
                            <option value="SCAN">SCAN</option>
                            <option value="SCAN_FAILED">SCAN FAILED</option>
                            <option value="LOGIN">LOGIN</option>
                            <option value="LOGOUT">LOGOUT</option>
                            <option value="LOGIN_FAILED">LOGIN FAILED</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-4">
                        <div class="d-flex gap-2">

                            <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="fas fa-filter me-1"></i>
                                Filter
                            </button>

                            <button type="button" class="btn btn-secondary" id="btn-reset">
                                <i class="fas fa-sync-alt me-1"></i>
                                Reset
                            </button>

                            <button type="button" class="btn btn-success" id="btn-export">
                                <i class="fas fa-file-excel me-1"></i>
                                Export Excel
                            </button>

                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- Table --}}
        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table id="audit-logs-table" class="table table-bordered table-hover align-middle w-100">

                        <thead>

                            <tr>

                                <th width="160">
                                    Waktu
                                </th>

                                <th width="130">
                                    User
                                </th>

                                <th width="130">
                                    Module
                                </th>

                                <th width="120">
                                    Action
                                </th>

                                <th width="180">
                                    Object
                                </th>

                                <th>
                                    Description
                                </th>

                                <th width="140">
                                    IP Address
                                </th>

                                <th width="80">
                                    #
                                </th>

                            </tr>

                        </thead>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        $(function() {

            const table = $('#audit-logs-table').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: @json(route('super.audit-logs.dt')),

                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.module = $('#module').val();
                        d.action = $('#action').val();
                    }
                },

                order: [
                    [0, 'desc']
                ],

                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name',
                        defaultContent: 'System'
                    },
                    {
                        data: 'module',
                        name: 'module'
                    },
                    {
                        data: 'action',
                        name: 'action',

                        render: function(data, type, row) {

                            if (!data) {
                                return '-';
                            }

                            const action = data.toUpperCase();

                            let badge = 'bg-secondary';

                            switch (action) {

                                case 'CREATE':
                                    badge = 'bg-success';
                                    break;

                                case 'UPDATE':
                                    badge = 'bg-warning text-dark';
                                    break;

                                case 'DELETE':
                                    badge = 'bg-danger';
                                    break;

                                case 'ASSIGN':
                                case 'UNASSIGN':
                                case 'SCAN':
                                    badge = 'bg-primary';
                                    break;

                                case 'SCAN_FAILED':
                                    badge = 'bg-warning text-dark';
                                    break;

                                case 'IMPORT':
                                case 'EXPORT':
                                    badge = 'bg-info text-dark';
                                    break;
                            }

                            return `
            <span class="badge ${badge}">
                ${action}
            </span>
        `;
                        }
                    },
                    {
                        data: 'object',
                        name: 'auditable_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                        defaultContent: '-'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]




            });


            $('#btn-filter').on('click', function() {

                table.ajax.reload();

            });


            $('#btn-reset').on('click', function() {

                $('#date_from').val('');

                $('#date_to').val('');

                $('#module').val('');

                $('#action').val('');

                table.ajax.reload();

            });




            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });


            $('#btn-reset').on('click', function() {

                $('#date_from').val('{{ now()->format('Y-m-d') }}');
                $('#date_to').val('{{ now()->format('Y-m-d') }}');

                $('#module').val('');
                $('#action').val('');

                table.ajax.reload();
            });


            $('#btn-export').on('click', function() {

                const params = new URLSearchParams({
                    date_from: $('#date_from').val() || '',
                    date_to: $('#date_to').val() || '',
                    module: $('#module').val() || '',
                    action: $('#action').val() || '',
                });

                window.location.href =
                    "{{ route('super.audit-logs.export') }}" +
                    '?' +
                    params.toString();
            });

        });
    </script>
@endpush
