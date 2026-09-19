@extends('layouts.admin')

@section('content')
    <div class="container-fluid scan-records-page">

        {{-- =====================================================
             PAGE HEADER
             ====================================================== --}}
        <div class="scan-records-header">
            <h4 class="scan-records-title">Scan Records</h4>
            <p class="scan-records-description">
                Riwayat penggunaan tiket.
            </p>
        </div>


        {{-- =====================================================
             FILTER CARD
             ====================================================== --}}
        <div class="scan-filter-card">

            <div class="scan-filter-grid">

                {{-- PERIODE --}}
                <div class="scan-filter-field scan-filter-period">

                    <label class="scan-filter-label">
                        Periode <span class="scan-filter-required">*</span>
                    </label>

                    <div class="scan-period">

                        <div class="scan-period-item">
                            <i data-feather="calendar"></i>

                            <input type="date" id="date_from" value="{{ now()->format('Y-m-d') }}"
                                aria-label="Tanggal mulai">
                        </div>

                        <span class="scan-period-separator">-</span>

                        <div class="scan-period-item">
                            <input type="date" id="date_to" value="{{ now()->format('Y-m-d') }}"
                                aria-label="Tanggal akhir">
                        </div>

                    </div>

                </div>


                {{-- USERNAME --}}
                <div class="scan-filter-field">

                    <label for="user_id" class="scan-filter-label">
                        Username
                    </label>

                    <select id="user_id" class="scan-filter-select">
                        <option value="">All User</option>

                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- OUTLET --}}
                <div class="scan-filter-field">

                    <label for="outlet_id" class="scan-filter-label">
                        Outlet
                    </label>

                    <select id="outlet_id" class="scan-filter-select">
                        <option value="">All Outlet</option>

                        @foreach ($outlets as $outlet)
                            <option value="{{ $outlet->id }}">
                                {{ $outlet->outlet_name }}
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- OUTLET TYPE --}}
                <div class="scan-filter-field">

                    <label for="outlet_type" class="scan-filter-label">
                        Outlet Type
                    </label>

                    <select id="outlet_type" class="scan-filter-select">
                        <option value="">All Outlet Type</option>

                        @foreach ($outletTypes as $type)
                            <option value="{{ $type }}">
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTION BUTTONS
             ====================================================== --}}
        <div class="scan-filter-actions">

            <button type="button" id="btnFilter" class="scan-action-btn scan-action-primary">
                <i data-feather="search"></i>
                <span>Tampilkan</span>
            </button>

            <button type="button" id="btnExport" class="scan-action-btn scan-action-success">
                <i data-feather="download"></i>
                <span>Export Excel</span>
            </button>

        </div>


        {{-- =====================================================
             REKAP TIKET UNIK PER OUTLET
             ====================================================== --}}
        <div class="scan-table-card mb-4">

            <div class="scan-table-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold">Rekap Tiket per Outlet</h6>
                        <small class="text-muted">
                            QR Code yang discan berulang di outlet yang sama dihitung 1 tiket.
                        </small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="outletSummaryTable" class="table table-bordered align-middle w-100 mb-0">
                        <thead>
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Kode Outlet</th>
                                <th>Nama Outlet</th>
                                <th style="width:180px;" class="text-end">Tiket Unik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        {{-- =====================================================
             TABLE
             ====================================================== --}}
        <div class="scan-table-card">

            <div class="scan-table-body">

                <div class="table-responsive">

                    <table id="scanRecordsTable" class="table table-bordered align-middle w-100">

                        <thead>
                            <tr>
                                <th>No Tiket</th>
                                <th>QR Code</th>
                                <th>Ticket Type</th>
                                <th>Operator</th>
                                <th>Outlet</th>
                                <th>Outlet Type</th>
                                <th>Method</th>
                                <th>Scanned At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


{{-- =========================================================
     STYLES
     ========================================================== --}}
@push('styles')
    <style>
        :root {
            --scan-primary: #6366f1;
            --scan-primary-hover: #4f46e5;

            --scan-success: #16a34a;
            --scan-success-hover: #15803d;

            --scan-danger: #dc2626;
            --scan-danger-hover: #b91c1c;

            --scan-text: #111827;
            --scan-muted: #64748b;

            --scan-border: #e5e7eb;
            --scan-border-input: #d1d5db;

            --scan-soft: #f8fafc;
            --scan-white: #ffffff;

            --scan-radius: 8px;
        }


        /* =========================================================
                   PAGE
                   ========================================================== */

        .scan-records-page {
            padding-bottom: 32px;
        }


        /* =========================================================
                   HEADER
                   ========================================================== */

        .scan-records-header {
            margin-bottom: 22px;
        }

        .scan-records-title {
            margin: 0 0 4px;

            color: var(--scan-text);

            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
        }

        .scan-records-description {
            margin: 0;

            color: var(--scan-muted);

            font-size: 13px;
            line-height: 1.5;
        }


        /* =========================================================
                   FILTER
                   ========================================================== */

        .scan-filter-card {
            width: 100%;

            padding: 18px;

            background: var(--scan-white);

            border: 1px solid var(--scan-border);
            border-radius: 10px;

            box-shadow: 0 3px 12px rgba(15, 23, 42, .06);
        }

        .scan-filter-grid {
            display: grid;

            grid-template-columns:
                minmax(250px, 1.35fr) minmax(170px, .85fr) minmax(200px, 1.1fr) minmax(180px, .95fr);

            gap: 16px;

            align-items: end;
        }

        .scan-filter-field {
            min-width: 0;
        }

        .scan-filter-label {
            display: block;

            margin: 0 0 7px;

            color: #374151;

            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }

        .scan-filter-required {
            color: var(--scan-danger);
        }


        /* =========================================================
                   SELECT
                   ========================================================== */

        .scan-filter-select {
            width: 100%;
            height: 42px;

            padding: 0 34px 0 12px;

            background-color: var(--scan-white);

            border: 1px solid var(--scan-border-input);
            border-radius: 6px;

            color: #374151;

            font-size: 13px;

            outline: none;

            cursor: pointer;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .scan-filter-select:hover {
            border-color: #9ca3af;
        }

        .scan-filter-select:focus {
            border-color: var(--scan-primary);

            box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
        }


        /* =========================================================
                   PERIOD
                   ========================================================== */

        .scan-period {
            width: 100%;
            height: 42px;

            display: flex;
            align-items: center;

            padding: 0 10px;

            background: var(--scan-white);

            border: 1px solid var(--scan-border-input);
            border-radius: 6px;

            gap: 8px;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .scan-period:focus-within {
            border-color: var(--scan-primary);

            box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
        }

        .scan-period-item {
            min-width: 0;
            flex: 1;

            display: flex;
            align-items: center;

            gap: 6px;
        }

        .scan-period-item svg {
            width: 15px;
            height: 15px;

            flex: 0 0 auto;

            color: #94a3b8;
        }

        .scan-period-item input {
            width: 100%;
            min-width: 0;
            height: 30px;

            padding: 0;

            background: transparent;

            border: 0;
            outline: 0;

            color: #374151;

            font-size: 12px;
            font-weight: 500;
        }

        .scan-period-item input::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: .8;
        }

        .scan-period-separator {
            flex: 0 0 auto;

            color: #94a3b8;

            font-size: 12px;
            font-weight: 600;
        }


        /* =========================================================
                   BUTTONS
                   ========================================================== */

        .scan-filter-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;

            gap: 8px;

            margin: 12px 0 20px;
        }

        .scan-action-btn {
            height: 40px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 0 16px;

            border: 0;
            border-radius: 6px;

            color: #ffffff;

            font-size: 13px;
            font-weight: 600;

            white-space: nowrap;

            cursor: pointer;

            transition:
                background-color .15s ease,
                transform .15s ease,
                box-shadow .15s ease;
        }

        .scan-action-btn svg {
            width: 16px;
            height: 16px;

            flex: 0 0 auto;
        }

        .scan-action-btn:hover {
            transform: translateY(-1px);
        }

        .scan-action-btn:active {
            transform: translateY(0);
        }

        .scan-action-primary {
            background: var(--scan-primary);
        }

        .scan-action-primary:hover {
            background: var(--scan-primary-hover);
        }

        .scan-action-success {
            background: var(--scan-success);
        }

        .scan-action-success:hover {
            background: var(--scan-success-hover);
        }


        /* =========================================================
                   TABLE CARD
                   ========================================================== */

        .scan-table-card {
            width: 100%;

            background: var(--scan-white);

            border: 1px solid var(--scan-border);
            border-radius: 10px;

            box-shadow: 0 3px 12px rgba(15, 23, 42, .05);

            overflow: hidden;
        }

        .scan-table-body {
            padding: 20px;
        }


        /* =========================================================
                   TABLE
                   ========================================================== */

        #scanRecordsTable {
            width: 100% !important;

            margin: 0 !important;

            font-size: 13px;
        }

        #scanRecordsTable thead th {
            padding: 12px 14px;

            background: var(--scan-soft);

            border-bottom: 1px solid var(--scan-border);

            color: #64748b;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;

            white-space: nowrap;
            vertical-align: middle;
        }

        #scanRecordsTable tbody td {
            padding: 12px 14px;

            color: #1e293b;

            border-bottom: 1px solid #eef2f7;

            white-space: nowrap;
            vertical-align: middle;
        }

        #scanRecordsTable tbody tr {
            transition: background-color .12s ease;
        }

        #scanRecordsTable tbody tr:hover {
            background: #f8fafc;
        }

        #scanRecordsTable tbody tr:last-child td {
            border-bottom: 0;
        }


        /* =========================================================
                   DATATABLE
                   ========================================================== */

        #scanRecordsTable_wrapper {
            width: 100%;
        }

        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 14px;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            color: #374151;

            font-size: 13px;
            font-weight: 400;
        }

        .dataTables_wrapper .dataTables_length select {
            height: 36px;

            margin: 0 5px;

            padding: 4px 30px 4px 10px;

            background: #ffffff;

            border: 1px solid var(--scan-border-input);
            border-radius: 6px;

            color: #374151;

            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 180px;
            height: 36px;

            margin-left: 6px;

            padding: 6px 10px;

            background: #ffffff;

            border: 1px solid var(--scan-border-input);
            border-radius: 6px;

            color: #374151;

            outline: none;

            transition:
                border-color .15s ease,
                box-shadow .15s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--scan-primary);

            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 12px;

            color: #64748b;

            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 8px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 36px;
            height: 36px;

            display: inline-flex !important;
            align-items: center;
            justify-content: center;

            margin-left: 3px !important;
            padding: 0 10px !important;

            border: 1px solid transparent !important;
            border-radius: 6px !important;

            color: #64748b !important;

            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;

            border-color: #e2e8f0 !important;

            color: #334155 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--scan-primary) !important;

            border-color: var(--scan-primary) !important;

            color: #ffffff !important;
        }


        /* =========================================================
                   ACTION DELETE
                   ========================================================== */

        .btn-delete-scan {
            width: 30px;
            height: 30px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 !important;

            border-radius: 6px;

            background: var(--scan-danger) !important;

            color: #ffffff !important;

            cursor: pointer;

            transition:
                background-color .15s ease,
                transform .15s ease,
                opacity .15s ease;
        }

        .btn-delete-scan:hover {
            background: var(--scan-danger-hover) !important;

            color: #ffffff !important;

            transform: translateY(-1px);
        }

        .btn-delete-scan i {
            font-size: 12px;
            line-height: 1;
        }


        /* =========================================================
                   RESPONSIVE
                   ========================================================== */

        @media (max-width: 1200px) {

            .scan-filter-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .scan-filter-period {
                grid-column: span 2;
            }

        }


        @media (max-width: 768px) {

            .scan-records-title {
                font-size: 20px;
            }

            .scan-filter-card {
                padding: 14px;
            }

            .scan-filter-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .scan-filter-period {
                grid-column: auto;
            }

            .scan-filter-actions {
                justify-content: stretch;
            }

            .scan-action-btn {
                flex: 1;
            }

            .scan-table-body {
                padding: 14px;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                width: 100%;
                text-align: left;
            }

            .dataTables_wrapper .dataTables_filter {
                margin-top: 8px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin: 6px 0 0;
            }

        }


        @media (max-width: 480px) {

            .scan-filter-actions {
                flex-direction: column;
            }

            .scan-action-btn {
                width: 100%;
                flex: none;
            }

            .scan-period-item input {
                font-size: 11px;
            }

        }
    </style>
@endpush


{{-- =========================================================
     SCRIPTS
     ========================================================== --}}
@push('scripts')
    <script>
        let scanRecordsTable;

        $(document).ready(function() {

            /* =====================================================
               FEATHER
               ====================================================== */

            if (window.feather) {
                feather.replace();
            }


            /* =====================================================
               REKAP OUTLET
               ====================================================== */

            function renderOutletSummary(rows) {

                const $tbody = $('#outletSummaryTable tbody');

                $tbody.empty();

                if (!rows || rows.length === 0) {
                    $tbody.html(`
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data sesuai filter.
                            </td>
                        </tr>
                    `);
                    return;
                }

                rows.forEach(function(row, index) {
                    $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${escapeHtml(row.outlet_code ?? '-')}</td>
                            <td>${escapeHtml(row.outlet_name ?? '-')}</td>
                            <td class="text-end fw-bold">
                                ${Number(row.total_tiket ?? 0).toLocaleString('id-ID')}
                            </td>
                        </tr>
                    `);
                });
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            $('#scanRecordsTable').on('xhr.dt', function(e, settings, json) {
                renderOutletSummary(json?.outlet_summary ?? []);
            });


            /* =====================================================
               DATATABLE
               ====================================================== */

            scanRecordsTable = $('#scanRecordsTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: false,

                autoWidth: false,

                ajax: {
                    url: "{{ route('super.scan-records.dt') }}",

                    data: function(d) {

                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.user_id = $('#user_id').val();
                        d.outlet_id = $('#outlet_id').val();
                        d.outlet_type = $('#outlet_type').val();

                    }
                },

                columns: [

                    {
                        data: 'no_tiket',
                        name: 'no_tiket',
                        defaultContent: '-'
                    },

                    {
                        data: 'qrcode',
                        name: 'qrcode',
                        defaultContent: '-'
                    },

                    {
                        data: 'ticket_type',
                        name: 'ticket_type',
                        defaultContent: '-'
                    },

                    {
                        data: 'user_name',
                        name: 'user.name',
                        defaultContent: '-'
                    },

                    {
                        data: 'outlet_name',
                        name: 'outlet.outlet_name',
                        defaultContent: '-'
                    },

                    {
                        data: 'outlet_type',
                        name: 'outlet.outlet_type',
                        defaultContent: '-'
                    },

                    {
                        data: 'scan_method',
                        name: 'scan_method',
                        defaultContent: '-'
                    },

                    {
                        data: 'scanned_at',
                        name: 'scanned_at',
                        defaultContent: '-'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        defaultContent: '-'
                    }

                ],

                order: [
                    [7, 'desc']
                ]

            });


            /* =====================================================
               TAMPILKAN
               ====================================================== */

            $('#btnFilter').on('click', function() {

                const dateFrom = $('#date_from').val();
                const dateTo = $('#date_to').val();

                if (!dateFrom || !dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Periode belum lengkap',
                        text: 'Silakan pilih tanggal mulai dan tanggal akhir.'
                    });

                    return;
                }

                if (dateFrom > dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Periode tidak valid',
                        text: 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
                    });

                    return;
                }

                scanRecordsTable.ajax.reload(null, true);

            });


            /* =====================================================
               EXPORT EXCEL
               ====================================================== */

            $('#btnExport').on('click', function() {

                const dateFrom = $('#date_from').val();
                const dateTo = $('#date_to').val();
                const userId = $('#user_id').val();
                const outletId = $('#outlet_id').val();
                const outletType = $('#outlet_type').val();

                if (!dateFrom || !dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Periode belum lengkap',
                        text: 'Silakan pilih tanggal mulai dan tanggal akhir.'
                    });

                    return;
                }

                if (dateFrom > dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Periode tidak valid',
                        text: 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
                    });

                    return;
                }

                const params = new URLSearchParams();

                params.append('date_from', dateFrom);
                params.append('date_to', dateTo);

                if (userId) {
                    params.append('user_id', userId);
                }

                if (outletId) {
                    params.append('outlet_id', outletId);
                }

                if (outletType) {
                    params.append('outlet_type', outletType);
                }

                window.location.href =
                    "{{ route('super.scan-records.export') }}" +
                    '?' +
                    params.toString();

            });


            /* =====================================================
               DELETE
               ====================================================== */

            $(document).on('click', '.btn-delete-scan', function() {

                const url = $(this).data('url');

                if (!url) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'URL delete tidak ditemukan.'
                    });

                    return;
                }

                Swal.fire({

                    icon: 'warning',

                    title: 'Hapus Scan?',

                    text: 'Data scan ini akan dihapus.',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Hapus',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#dc2626',

                    cancelButtonColor: '#64748b'

                }).then(function(result) {

                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({

                        url: url,

                        type: 'DELETE',

                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function(response) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Berhasil',

                                text: response.message ||
                                    'Data scan berhasil dihapus.',

                                showConfirmButton: false,

                                timer: 1500

                            });

                            scanRecordsTable.ajax.reload(null, false);

                        },

                        error: function(xhr) {

                            Swal.fire({

                                icon: 'error',

                                title: 'Gagal',

                                text: xhr.responseJSON?.message ||
                                    'Data scan gagal dihapus.'

                            });

                        }

                    });

                });

            });

        });
    </script>
@endpush
