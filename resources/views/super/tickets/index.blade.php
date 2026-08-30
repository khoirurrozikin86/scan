@extends('layouts.admin')
@section('title', 'Tickets')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Ticket Management</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Tickets
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
                            Ticket
                        </h6>

                        <div class="d-flex gap-2">
                            {{-- <a href="javascript:void(0)" id="btnExport" class="btn btn-outline-secondary btn-sm">
                                Export Excel
                            </a> --}}

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
                        <table id="categories-table" class="table w-100">
                            <thead>
                                <th>No Ticket</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>

                                <th>Assigned To</th>
                                <th>Created Date</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Modal Create/Edit --}}
<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="itemForm">

                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalTitle">
                        New Ticket
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">
                                Ticket Title
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="title" name="title">

                            <div class="invalid-feedback" id="titleErr"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Category
                            </label>

                            <select class="form-select" id="category_id" name="category_id">

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="categoryErr"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Assigned Person
                            </label>

                            <select class="form-select" id="assigned_user_id" name="assigned_user_id">

                                <option value="">
                                    Select User
                                </option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($user->id == auth()->id())>

                                        {{ $user->name }}

                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Priority
                            </label>

                            <select class="form-select" id="priority" name="priority">

                                <option value="Low">
                                    Low
                                </option>

                                <option value="Medium" selected>
                                    Medium
                                </option>

                                <option value="High">
                                    High
                                </option>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Status
                            </label>

                            <select class="form-select" id="status" name="status">

                                <option value="Open">
                                    Open
                                </option>

                                <option value="In Progress">
                                    In Progress
                                </option>

                                <option value="Resolved">
                                    Resolved
                                </option>

                                <option value="Closed">
                                    Closed
                                </option>

                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Description
                            </label>

                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Notes
                            </label>

                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnSaveItem" class="btn btn-primary">
                        Save Ticket
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@push('vendor-styles')
    <link rel="stylesheet" href="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
@endpush

@push('vendor-scripts')
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('scripts')
    <script>
        (function($) {

            'use strict';

            function safeFeatherReplace() {
                if (!window.feather || !feather.icons) return;

                document.querySelectorAll('[data-feather]').forEach(el => {
                    const name = el.getAttribute('data-feather') || 'settings';

                    if (!feather.icons[name]) {
                        el.setAttribute('data-feather', 'settings');
                    }
                });

                try {
                    feather.replace();
                } catch (e) {}
            }

            const DT_SEL = '#categories-table';

            const modalEl = document.getElementById('itemModal');
            const bsModal = new bootstrap.Modal(modalEl);

            const $form = $('#itemForm');
            const $btnSave = $('#btnSaveItem');

            const $name = $('#name');
            // const $description = $('#description');



            const $title = $('#title');
            const $category = $('#category_id');
            const $assignedUser = $('#assigned_user_id');
            const $priority = $('#priority');
            const $status = $('#status');
            const $description = $('#description');
            const $notes = $('#notes');

            const $nameErr = $('#nameErr');
            const $descriptionErr = $('#descriptionErr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            function clearErrors() {
                [$name, $description].forEach($el =>
                    $el.removeClass('is-invalid')
                );

                $nameErr.text('');
                $descriptionErr.text('');
            }

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
                        text: msg || 'Something went wrong'
                    });
                }
            }

            function reloadTable() {
                $(DT_SEL).DataTable().ajax.reload(null, false);
            }

            $(DT_SEL).DataTable({
                processing: true,
                serverSide: true,

                ajax: '{{ route('super.tickets.dt') }}',

                columns: [{
                        data: 'ticket_number',
                        name: 'ticket_number'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },



                    {
                        data: 'assigned_user',
                        name: 'assigned_user'
                    },
                    {
                        data: 'created_date',
                        name: 'created_date'
                    },

                    {
                        data: 'progress',
                        name: 'progress',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],

                order: [
                    [6, 'desc']
                ]

            }).on('draw.dt', safeFeatherReplace);

            $('#btnNewItem').on('click', function() {

                clearErrors();

                $form[0].reset();

                $form
                    .data('mode', 'create')
                    .data(
                        'action',
                        '{{ route('super.tickets.store') }}'
                    );

                $('#itemModalTitle').text('New Category');

                bsModal.show();
            });

            $(document).on('click', '.btn-edit-role', function() {

                clearErrors();

                $form[0].reset();

                let payload = {};

                try {
                    payload = JSON.parse(
                        $(this).attr('data-payload') || '{}'
                    );
                } catch (e) {}

                $form
                    .data('mode', 'edit')
                    .data(
                        'action',
                        $(this).data('update-url')
                    );

                $('#itemModalTitle').text('Edit Tickets');

                $title.val(payload.title || '');
                $category.val(payload.category_id || '');
                $assignedUser.val(payload.assigned_user_id || '');
                $priority.val(payload.priority || 'Medium');
                $status.val(payload.status || 'Open');
                $description.val(payload.description || '');
                $notes.val(payload.notes || '');

                bsModal.show();
            });

            $form.on('submit', function(e) {

                e.preventDefault();

                clearErrors();

                const mode = $form.data('mode');
                const action = $form.data('action');

                const fd = new FormData($form[0]);

                if (mode === 'edit') {
                    fd.append('_method', 'PUT');
                }

                $btnSave.prop('disabled', true)
                    .text('Saving...');

                $.ajax({
                        url: action,
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false
                    })
                    .done(res => {

                        toastOk(
                            res.message ||
                            (mode === 'edit' ?
                                'Updated' :
                                'Created')
                        );

                        bsModal.hide();

                        reloadTable();
                    })
                    .fail(xhr => {

                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON?.errors
                        ) {

                            const e = xhr.responseJSON.errors;

                            if (e.name) {
                                $name.addClass('is-invalid');
                                $nameErr.text(e.name[0]);
                            }

                            if (e.description) {
                                $description.addClass(
                                    'is-invalid'
                                );
                                $descriptionErr.text(
                                    e.description[0]
                                );
                            }

                        } else {

                            toastErr(
                                xhr.responseJSON?.message
                            );
                        }

                    })
                    .always(() => {

                        $btnSave.prop('disabled', false)
                            .text('Save');

                    });

            });

            $(document).on('click', '.btn-delete-role', function() {

                const url = $(this).data('url');

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete?',
                    text: 'Delete category?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete'
                }).then(r => {

                    if (!r.isConfirmed) return;

                    $.post(url, {
                            _method: 'DELETE'
                        })
                        .done(() => {
                            toastOk('Deleted');
                            reloadTable();
                        })
                        .fail(xhr => {
                            toastErr(
                                xhr.responseJSON?.message ||
                                'Failed'
                            );
                        });
                });

            });

            $('#btnExport').on('click', function() {

                const q =
                    $(DT_SEL).DataTable().search() || '';

                const url =
                    @json(route('super.categories.export'));

                window.location =
                    url +
                    '?q=' +
                    encodeURIComponent(q);

            });

            modalEl.addEventListener(
                'hidden.bs.modal',
                clearErrors
            );



            $(document).on(
                'change',
                '.ticket-status',
                function() {

                    const id = $(this).data('id');
                    const status = $(this).val();

                    $.ajax({
                        url: `/super/tickets/${id}/status`,
                        method: 'POST',
                        data: {
                            _method: 'PUT',
                            status: status
                        },

                        success: function(res) {




                            reloadTable();

                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                timer: 1000,
                                showConfirmButton: false
                            });

                        },

                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            );

        })(jQuery);
    </script>
@endpush
