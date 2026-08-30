@extends('layouts.admin')
@section('title', 'Users')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Access</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">Users</h6>
                        <a href="javascript:void(0)" id="btnNewUser" class="btn btn-primary btn-sm">+ New</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="users-table" class="table w-100">
                            <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Actions</th> {{-- <== Status dihapus --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Modal Create/Edit User (tanpa field Active) --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="userName" class="form-control" required>
                        <div class="invalid-feedback" id="userNameErr"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="userEmail" class="form-control" required>
                        <div class="invalid-feedback" id="userEmailErr"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="userPassword" class="form-control"
                            placeholder="(leave blank to keep)">
                    </div>
                    {{-- Field Active DIHAPUS --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSaveUser" class="btn btn-primary btn-sm">Save</button>
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

            const DT_SEL = '#users-table';
            const modalEl = document.getElementById('userModal');
            const bsModal = new bootstrap.Modal(modalEl);
            const $form = $('#userForm');
            const $btnNew = $('#btnNewUser');
            const $btnSave = $('#btnSaveUser');
            const $title = $('#userModalTitle');

            const $name = $('#userName');
            const $email = $('#userEmail');
            const $pass = $('#userPassword');
            const $nameErr = $('#userNameErr');
            const $emailErr = $('#userEmailErr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            function clearErrors() {
                [$name, $email].forEach($f => $f.removeClass('is-invalid'));
                $nameErr.text('');
                $emailErr.text('');
            }

            function toastOk(msg) {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    timer: 1800,
                    showConfirmButton: false,
                    title: msg || 'Success'
                });
            }

            function toastErr(msg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg || 'Something went wrong'
                });
            }

            function reloadTable() {
                $(DT_SEL).DataTable().ajax.reload(null, false);
            }

            // DataTable: tanpa kolom Status
            $(DT_SEL).DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('super.user.dt') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (!data) return '-';
                            // kalau relasi roles dikirim sebagai array of objects [{id,name}, ...]
                            if (Array.isArray(data)) return data.map(r => r.name).join(', ');
                            // fallback kalau struktur berbeda
                            if (typeof data === 'object' && data.data) return data.data.map(r => r.name)
                                .join(', ');
                            return String(data);
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            }).on('draw.dt', function() {
                if (window.feather) feather.replace();
            });

            // New
            $btnNew.on('click', function() {
                clearErrors();
                $form.data('mode', 'create');
                $form.data('action',
                    '{{ route('super.user.store') }}'); // <- perbaiki route (plural)
                $title.text('New User');
                $form[0].reset();
                bsModal.show();
            });

            // Edit
            $(document).on('click', '.btn-edit-role', function() {
                const $btn = $(this);
                let payload = {};
                try {
                    payload = JSON.parse($btn.attr('data-payload') || '{}');
                } catch (e) {}

                $form.data('mode', 'edit').data('action', $btn.data('update-url'));
                $title.text('Edit');
                // pakai payload dulu, kalau kosong fallback ke data-* lama
                $name.val(payload.name ?? $btn.data('name') ?? '');
                $('#roleGroup, #userEmail, #permGroup') // pilih yang relevan per halaman
                    .val(payload.group_name ?? payload.email ?? $btn.data('group-name') ?? '');
                bsModal.show();
            });

            // Submit (Create/Update)
            $form.on('submit', function(e) {
                e.preventDefault();
                clearErrors();
                const mode = $form.data('mode');
                const action = $form.data('action');
                const data = {
                    name: $name.val(),
                    email: $email.val(),
                    password: $pass.val(),
                };
                if (mode === 'edit') data._method = 'PUT';

                $btnSave.prop('disabled', true).text('Saving...');
                $.post(action, data)
                    .done(res => {
                        toastOk(res.message || (mode === 'edit' ? 'User updated' : 'User created'));
                        bsModal.hide();
                        reloadTable();
                    })
                    .fail(xhr => {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.name) {
                                $name.addClass('is-invalid');
                                $nameErr.text(errs.name[0]);
                            }
                            if (errs.email) {
                                $email.addClass('is-invalid');
                                $emailErr.text(errs.email[0]);
                            }
                            return;
                        }
                        toastErr(xhr.responseJSON?.message);
                    })
                    .always(() => $btnSave.prop('disabled', false).text('Save'));
            });

            // Delete
            $(document).on('click', '.btn-delete-role', function() {
                const $btn = $(this);
                const url = $btn.data('url');
                let payload = {};
                try {
                    payload = JSON.parse($btn.attr('data-payload') || '{}');
                } catch (e) {}
                const name = payload.name ?? $btn.data('name') ?? '';

                Swal.fire({
                        icon: 'warning',
                        title: 'Delete?',
                        text: `Delete ${name}?`,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete'
                    })
                    .then(r => {
                        if (!r.isConfirmed) return;
                        $.post(url, {
                                _method: 'DELETE'
                            })
                            .done(() => {
                                toastOk('Deleted');
                                reloadTable();
                            })
                            .fail(xhr => toastErr(xhr.responseJSON?.message || 'Failed'));
                    });
            });
            modalEl.addEventListener('hidden.bs.modal', clearErrors);

        })(jQuery);
    </script>
@endpush
