@extends('layouts.admin')
@section('title', 'Categories')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Ticket Management</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Categories
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
                            Categories
                        </h6>

                        <div class="d-flex gap-2">
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
                        <table id="categories-table" class="table w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
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

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="itemForm">

                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalTitle">
                        New Category
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">
                                Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" id="name" name="name" required>

                            <div class="invalid-feedback" id="nameErr"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Description
                            </label>

                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>

                            <div class="invalid-feedback" id="descriptionErr"></div>
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
            const $description = $('#description');

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

                ajax: '{{ route('super.categories.dt') }}',

                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],

                order: [
                    [2, 'desc']
                ]

            }).on('draw.dt', safeFeatherReplace);

            $('#btnNewItem').on('click', function() {

                clearErrors();

                $form[0].reset();

                $form
                    .data('mode', 'create')
                    .data(
                        'action',
                        '{{ route('super.categories.store') }}'
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

                $('#itemModalTitle').text('Edit Category');

                $name.val(payload.name || '');
                $description.val(payload.description || '');

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

        })(jQuery);
    </script>
@endpush
