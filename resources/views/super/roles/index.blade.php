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
                        {{-- <a href="{{ route('super.roles.create') }}" class="btn btn-primary btn-sm">
                            + New
                        </a> --}}
                        <a href="javascript:void(0)" id="btnNewRole" class="btn btn-primary btn-sm">+ New</a>

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

{{-- Modal --}}
<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="roleForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalTitle">New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="roleName" name="name" type="text" class="form-control" required
                            placeholder="e.g. admin, editor">
                        <div class="invalid-feedback" id="roleNameErr"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSaveRole" class="btn btn-primary btn-sm">Save</button>
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

            // ====== Konstanta elemen ======
            const DT_SEL = '#roles-table';
            const $form = $('#roleForm');
            const $title = $('#roleModalTitle');
            const $name = $('#roleName');
            const $nameErr = $('#roleNameErr');
            const $btnNew = $('#btnNewRole');
            const $btnSave = $('#btnSaveRole');
            const modalEl = document.getElementById('roleModal');
            const bsModal = new bootstrap.Modal(modalEl);

            // ====== CSRF setup ======
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            // ====== Utils ======
            function reloadTable() {
                if ($.fn.DataTable.isDataTable(DT_SEL)) $(DT_SEL).DataTable().ajax.reload(null, false);
            }

            function toastOk(msg) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: msg || 'Success',
                    timer: 1800,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            }

            function toastErr(msg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg || 'Something went wrong'
                });
            }

            function clearErrors() {
                $name.removeClass('is-invalid');
                $nameErr.text('');
            }

            function openModal(mode, payload) {
                clearErrors();
                $form.data('mode', mode);
                if (mode === 'create') {
                    $title.text('New Role');
                    $form.data('action', @json(route('super.roles.store')));
                    $name.val('');
                } else {
                    $title.text('Edit Role');
                    $form.data('action', payload.update_url);
                    $name.val(payload.name || '');
                }
                bsModal.show();
            }

            // ====== DataTable ======
            $(DT_SEL).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: @json(route('super.roles.dt')),
                    data: function(d) {
                        // kalau tidak pakai custom search, hapus baris ini
                        d.custom_search = $('#role-search').val();
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

            // Feather icons setelah redraw
            $(DT_SEL).on('draw.dt', function() {
                if (window.feather) feather.replace();
            });

            // ====== New ======
            $btnNew.on('click', function() {
                openModal('create');
            });

            // ====== Edit (delegated dari dropdown actions) ======
            $(document).on('click', '.btn-edit-role', function() {
                openModal('edit', {
                    update_url: $(this).data('update-url'),
                    name: $(this).data('name')
                });
            });

            // ====== Submit Create/Update (AJAX) ======
            $form.on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                const mode = $form.data('mode'); // 'create'|'edit'
                const action = $form.data('action');
                const data = {
                    name: $name.val()
                };
                const payload = (mode === 'edit') ? $.extend({
                    _method: 'PUT'
                }, data) : data;

                $btnSave.prop('disabled', true).text('Saving...');

                $.post(action, payload)
                    .done(function(res) {
                        bsModal.hide();
                        toastOk(res.message || (mode === 'edit' ? 'Role updated' : 'Role created'));
                        reloadTable();
                        if (mode === 'create') $form[0].reset();
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            if (errs.name && errs.name.length) {
                                $name.addClass('is-invalid');
                                $nameErr.text(errs.name[0]);
                                return;
                            }
                        }
                        toastErr(xhr.responseJSON?.message);
                    })
                    .always(function() {
                        $btnSave.prop('disabled', false).text('Save');
                    });
            });

            // ====== Delete (delegated + Swal confirm + AJAX) ======
            $(document).on('click', '.btn-delete-role', function() {
                const url = $(this).data('url');
                const name = $(this).data('name') || '';
                const ask = $(this).data('confirm') || 'Delete this item?';
                const disabled = $(this).is(':disabled');
                if (disabled) return;

                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: ask,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (!result.isConfirmed) return;

                    $.post(url, {
                            _method: 'DELETE'
                        })
                        .done(function(res) {
                            toastOk(res.message || ('Role ' + name + ' deleted'));
                            reloadTable();
                        })
                        .fail(function(xhr) {
                            toastErr(xhr.responseJSON?.message || 'Failed to delete');
                        });
                });
            });

            // ====== Optional: search manual kalau dipakai ======
            $('#role-search-btn').on('click', function(e) {
                e.preventDefault();
                reloadTable();
            });
            $('#role-search').on('keyup', function() {
                $(DT_SEL).DataTable().search(this.value).draw();
            });

            // ====== Bersihkan error tiap modal tertutup ======
            modalEl.addEventListener('hidden.bs.modal', clearErrors);

        })(jQuery);
    </script>
@endpush
