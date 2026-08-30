@extends('layouts.admin')

@section('title', 'Ticket QRCode')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Master</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Ticket QRCode
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <h6 class="card-title mb-0">
                            Master Ticket QRCode
                        </h6>

                        <div class="d-flex gap-2">

                            {{-- Import Excel --}}
                            <button type="button" id="btnImport" class="btn btn-outline-success btn-sm">
                                <i data-feather="upload" class="icon-sm me-1"></i>
                                Import Excel
                            </button>

                            <a href="javascript:void(0)" id="btnExport" class="btn btn-outline-secondary btn-sm">
                                Export Excel
                            </a>

                            <a href="javascript:void(0)" id="btnNewItem" class="btn btn-primary btn-sm">
                                + New
                            </a>

                        </div>

                    </div>


                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <div class="table-responsive">

                        <table id="ticket-qrcode-table" class="table w-100">

                            <thead>
                                <tr>
                                    <th>No Tiket</th>
                                    <th>QR Code</th>
                                    <th>Ticket Type</th>
                                    <th>Remark</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection


{{-- ========================================================= --}}
{{-- MODAL CREATE / EDIT --}}
{{-- ========================================================= --}}

<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="itemForm">

                <div class="modal-header">

                    <h5 class="modal-title" id="itemModalTitle">
                        New Ticket QRCode
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- NO TIKET --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                No Tiket
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="no_tiket" name="no_tiket" required>

                            <div class="invalid-feedback" id="no_tiketErr">
                            </div>

                        </div>


                        {{-- QR CODE --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                QR Code
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="qrcode" name="qrcode" required>

                            <div class="invalid-feedback" id="qrcodeErr">
                            </div>

                        </div>


                        {{-- TICKET TYPE --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Ticket Type
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="ticket_type" name="ticket_type"
                                placeholder="Contoh: Wahana Sepuasnya" required>

                            <div class="invalid-feedback" id="ticket_typeErr">
                            </div>

                        </div>


                        {{-- REMARK --}}
                        <div class="col-md-12">

                            <label class="form-label">
                                Remark
                            </label>

                            <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>

                            <div class="invalid-feedback" id="remarkErr">
                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnSaveItem" class="btn btn-primary btn-sm">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL IMPORT EXCEL --}}
{{-- ========================================================= --}}

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="importForm">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Import Ticket QRCode
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">
                        <small>
                            Format Excel:
                            <strong>
                                no_tiket, qrcode, ticket_type, remark
                            </strong>
                        </small>
                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            File Excel
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file" class="form-control" id="import_file" name="file"
                            accept=".xlsx,.xls" required>

                        <div class="invalid-feedback" id="import_fileErr">
                        </div>

                    </div>

                    <div class="text-muted small">
                        <i data-feather="info" class="icon-sm"></i>
                        Data duplikat akan ditolak.
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnImportSubmit" class="btn btn-success btn-sm">
                        Import
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')
    <script>
        (function($) {

            'use strict';


            /* =========================================================
             * CONFIG
             * ========================================================= */

            const DT_SEL = '#ticket-qrcode-table';

            const modalEl =
                document.getElementById('itemModal');

            const bsModal =
                new bootstrap.Modal(modalEl);

            const $form =
                $('#itemForm');

            const $btnSave =
                $('#btnSaveItem');


            /* =========================================================
             * FORM FIELD
             * ========================================================= */

            const $noTiket =
                $('#no_tiket');

            const $qrcode =
                $('#qrcode');

            const $ticketType =
                $('#ticket_type');

            const $remark =
                $('#remark');


            const $noTiketErr =
                $('#no_tiketErr');

            const $qrcodeErr =
                $('#qrcodeErr');

            const $ticketTypeErr =
                $('#ticket_typeErr');

            const $remarkErr =
                $('#remarkErr');


            /* =========================================================
             * AJAX
             * ========================================================= */

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                    'X-Requested-With': 'XMLHttpRequest',

                    'Accept': 'application/json'

                }

            });


            /* =========================================================
             * FEATHER
             * ========================================================= */

            function safeFeatherReplace() {

                if (!window.feather ||
                    !feather.icons) {
                    return;
                }

                try {

                    feather.replace();

                } catch (e) {}

            }


            /* =========================================================
             * CLEAR ERROR
             * ========================================================= */

            function clearErrors() {

                [
                    $noTiket,
                    $qrcode,
                    $ticketType,
                    $remark
                ].forEach(function($el) {

                    $el.removeClass('is-invalid');

                });


                $noTiketErr.text('');
                $qrcodeErr.text('');
                $ticketTypeErr.text('');
                $remarkErr.text('');

            }


            /* =========================================================
             * TOAST
             * ========================================================= */

            function toastOk(msg) {

                if (window.Swal) {

                    Swal.fire({

                        toast: true,

                        icon: 'success',

                        position: 'top-end',

                        timer: 1800,

                        showConfirmButton: false,

                        title: msg || 'Success'

                    });

                }

            }


            function toastErr(msg) {

                if (window.Swal) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: msg ||
                            'Something went wrong'

                    });

                }

            }


            /* =========================================================
             * RELOAD DATATABLE
             * ========================================================= */

            function reloadTable() {

                $(DT_SEL)
                    .DataTable()
                    .ajax
                    .reload(null, false);

            }


            /* =========================================================
             * DATATABLE
             * ========================================================= */

            const table = $(DT_SEL).DataTable({

                processing: true,

                serverSide: true,

                responsive: false,

                autoWidth: false,

                ajax: '{{ route('super.ticket-qrcode.dt') }}',

                columns: [

                    {
                        data: 'no_tiket',
                        name: 'no_tiket'
                    },

                    {
                        data: 'qrcode',
                        name: 'qrcode'
                    },

                    {
                        data: 'ticket_type',
                        name: 'ticket_type'
                    },

                    {
                        data: 'remark',
                        name: 'remark'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },

                    {
                        data: 'actions',
                        name: 'actions',

                        orderable: false,

                        searchable: false,

                        className: 'text-center align-middle',

                        defaultContent: ''

                    }

                ],

                order: [
                    [0, 'desc']
                ],

                drawCallback: function() {

                    safeFeatherReplace();

                }

            });


            /* =========================================================
             * NEW
             * ========================================================= */

            $('#btnNewItem').on(
                'click',
                function() {

                    clearErrors();

                    $form[0].reset();

                    $form
                        .data('mode', 'create')
                        .data(
                            'action',
                            '{{ route('super.ticket-qrcode.store') }}'
                        );


                    $('#itemModalTitle')
                        .text('New Ticket QRCode');


                    bsModal.show();

                }
            );


            /* =========================================================
             * EDIT
             * ========================================================= */

            $(document).on(
                'click',
                '.btn-edit-role',
                function() {

                    clearErrors();

                    $form[0].reset();


                    let payload = {};


                    try {

                        payload = JSON.parse(
                            $(this).attr(
                                'data-payload'
                            ) || '{}'
                        );

                    } catch (e) {

                        console.error(
                            'Invalid payload',
                            e
                        );

                    }


                    $form
                        .data('mode', 'edit')
                        .data(
                            'action',
                            $(this).data(
                                'update-url'
                            )
                        );


                    $('#itemModalTitle')
                        .text('Edit Ticket QRCode');


                    $noTiket.val(
                        payload.no_tiket || ''
                    );

                    $qrcode.val(
                        payload.qrcode || ''
                    );

                    $ticketType.val(
                        payload.ticket_type || ''
                    );

                    $remark.val(
                        payload.remark || ''
                    );


                    bsModal.show();

                }
            );


            /* =========================================================
             * SAVE
             * ========================================================= */

            $form.on(
                'submit',
                function(e) {

                    e.preventDefault();

                    clearErrors();


                    const mode =
                        $form.data('mode');

                    const action =
                        $form.data('action');


                    const fd =
                        new FormData(
                            $form[0]
                        );


                    if (mode === 'edit') {

                        fd.append(
                            '_method',
                            'PUT'
                        );

                    }


                    $btnSave
                        .prop('disabled', true)
                        .text('Saving...');


                    $.ajax({

                            url: action,

                            method: 'POST',

                            data: fd,

                            processData: false,

                            contentType: false

                        })

                        .done(function(res) {

                            toastOk(
                                res.message ||
                                (
                                    mode === 'edit' ?
                                    'Updated' :
                                    'Created'
                                )
                            );


                            bsModal.hide();

                            reloadTable();

                        })

                        .fail(function(xhr) {

                            if (
                                xhr.status === 422 &&
                                xhr.responseJSON?.errors
                            ) {

                                const errors =
                                    xhr.responseJSON.errors;


                                if (errors.no_tiket) {

                                    $noTiket
                                        .addClass(
                                            'is-invalid'
                                        );

                                    $noTiketErr
                                        .text(
                                            errors.no_tiket[0]
                                        );

                                }


                                if (errors.qrcode) {

                                    $qrcode
                                        .addClass(
                                            'is-invalid'
                                        );

                                    $qrcodeErr
                                        .text(
                                            errors.qrcode[0]
                                        );

                                }


                                if (errors.ticket_type) {

                                    $ticketType
                                        .addClass(
                                            'is-invalid'
                                        );

                                    $ticketTypeErr
                                        .text(
                                            errors.ticket_type[0]
                                        );

                                }


                                if (errors.remark) {

                                    $remark
                                        .addClass(
                                            'is-invalid'
                                        );

                                    $remarkErr
                                        .text(
                                            errors.remark[0]
                                        );

                                }

                            } else {

                                toastErr(
                                    xhr.responseJSON?.message
                                );

                            }

                        })

                        .always(function() {

                            $btnSave
                                .prop('disabled', false)
                                .text('Save');

                        });

                }
            );


            /* =========================================================
             * DELETE
             * ========================================================= */

            $(document).on(
                'click',
                '.btn-delete-role',
                function() {

                    const url =
                        $(this).data('url');

                    const confirmText =
                        $(this).data('confirm') ||
                        'Delete this ticket?';


                    Swal.fire({

                        icon: 'warning',

                        title: 'Delete?',

                        text: confirmText,

                        showCancelButton: true,

                        confirmButtonText: 'Yes, delete',

                        cancelButtonText: 'Cancel'

                    }).then(function(result) {

                        if (!result.isConfirmed) {
                            return;
                        }


                        $.post(

                                url,

                                {
                                    _method: 'DELETE'
                                }

                            )

                            .done(function(res) {

                                toastOk(
                                    res.message ||
                                    'Deleted'
                                );

                                reloadTable();

                            })

                            .fail(function(xhr) {

                                toastErr(
                                    xhr.responseJSON?.message ||
                                    'Failed to delete'
                                );

                            });

                    });

                }
            );






            /* =========================================================
             * IMPORT EXCEL
             * ========================================================= */

            const importModalEl =
                document.getElementById('importModal');

            const bsImportModal =
                new bootstrap.Modal(importModalEl);

            const $importForm =
                $('#importForm');

            const $btnImportSubmit =
                $('#btnImportSubmit');


            /* OPEN IMPORT */

            $('#btnImport').on('click', function() {

                $importForm[0].reset();

                $('#import_file')
                    .removeClass('is-invalid');

                $('#import_fileErr')
                    .text('');

                bsImportModal.show();

            });


            /* SUBMIT IMPORT */

            $importForm.on('submit', function(e) {

                e.preventDefault();

                const file =
                    $('#import_file')[0].files[0];


                if (!file) {

                    $('#import_file')
                        .addClass('is-invalid');

                    $('#import_fileErr')
                        .text('Please select an Excel file.');

                    return;
                }


                $('#import_file')
                    .removeClass('is-invalid');

                $('#import_fileErr')
                    .text('');


                const formData =
                    new FormData(this);


                $btnImportSubmit
                    .prop('disabled', true)
                    .html('Importing...');


                $.ajax({

                        url: '{{ route('super.ticket-qrcode.import') }}',

                        method: 'POST',

                        data: formData,

                        processData: false,

                        contentType: false,

                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                            'X-Requested-With': 'XMLHttpRequest',

                            'Accept': 'application/json'
                        }

                    })

                    .done(function(res) {

                        bsImportModal.hide();

                        // Reload DataTable
                        reloadTable();

                        Swal.fire({
                            icon: 'success',
                            title: 'Import Berhasil',
                            text: res.message || 'Data berhasil diimport.',
                            confirmButtonText: 'OK'
                        }).then(function() {

                            reloadTable();

                        });

                    })

                    .fail(function(xhr) {

                        let message = 'Import gagal.';

                        if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Import Gagal',
                            text: message,
                            confirmButtonText: 'OK'
                        });

                    })

                    .always(function() {

                        $btnImportSubmit
                            .prop('disabled', false)
                            .html('Import');

                    });
            });


            /* =========================================================
             * EXPORT
             * ========================================================= */

            $('#btnExport').on(
                'click',
                function() {

                    const q =
                        table.search() || '';


                    const url =
                        @json(route('super.ticket-qrcode.export'));


                    window.location =
                        url +
                        '?q=' +
                        encodeURIComponent(q);

                }
            );


            /* =========================================================
             * MODAL CLOSED
             * ========================================================= */

            modalEl.addEventListener(
                'hidden.bs.modal',
                function() {

                    clearErrors();

                }
            );


        })(jQuery);
    </script>
@endpush
