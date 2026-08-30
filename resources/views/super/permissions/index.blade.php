{{-- resources/views/super/permissions/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Permissions')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Access</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permissions</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">Permissions</h6>
                        <a href="javascript:void(0)" id="btnNewPermission" class="btn btn-primary btn-sm">+ New</a>
                    </div>

                    @if (session('ok'))
                        <div class="alert alert-success">{{ session('ok') }}</div>
                    @endif



                    <div class="table-responsive">
                        <table id="permissions-table" class="table w-100">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Group Name</th>
                                    <th>Roles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            {{-- tbody diisi DataTables --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Modal --}}
<div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="permissionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalTitle">New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="permName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="permName" name="name" type="text" class="form-control" required
                            placeholder="e.g. users.view">
                        <div class="invalid-feedback" id="permNameErr"></div>
                    </div>


                    <div class="mb-3">
                        <label for="permGroup" class="form-label">Group</label>
                        <input id="permGroup" name="group_name" type="text" class="form-control"
                            placeholder="e.g. users, posts, reports">
                        <div class="invalid-feedback" id="permGroupErr"></div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSavePermission" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        (function($) {
            'use strict';

            const DT_SEL = '#permissions-table';
            const $form = $('#permissionForm');
            const $title = $('#permissionModalTitle');
            const $name = $('#permName');
            const $nameErr = $('#permNameErr');
            const $btnNew = $('#btnNewPermission');
            const $btnSave = $('#btnSavePermission');
            const modalEl = document.getElementById('permissionModal');
            const bsModal = new bootstrap.Modal(modalEl);
            const $group = $('#permGroup');
            const $groupErr = $('#permGroupErr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            function reloadTable() {
                if ($.fn.DataTable.isDataTable(DT_SEL)) $(DT_SEL).DataTable().ajax.reload(null, false);
            }

            function clearErrors() {
                $name.removeClass('is-invalid');
                $nameErr.text('');

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

            function openModal(mode, payload) {
                clearErrors();
                $form.data('mode', mode);
                if (mode === 'create') {
                    $title.text('New Permission');
                    $form.data('action', @json(route('super.permissions.store')));
                    $name.val('');
                    $group.val(''); // <— reset group
                } else {
                    $title.text('Edit Permission');
                    $form.data('action', payload.update_url);
                    $name.val(payload.name || '');
                    $group.val(payload.group_name || ''); // <— isi group saat edit
                }
                bsModal.show();
            }

            // DataTable
            $(DT_SEL).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: @json(route('super.permissions.dt')),
                    data: function(d) {
                        d.custom_search = $('#perm-search').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    }, {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'group_name',
                        name: 'group_name'
                    },
                    {
                        data: 'roles_count',
                        name: 'roles_count',
                        searchable: false
                    }, // dari withCount('roles')
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });

            $(DT_SEL).on('draw.dt', function() {
                if (window.feather) feather.replace();
            });

            // New
            $btnNew.on('click', function() {
                openModal('create');
            });

            // Edit dari dropdown (pakai partial table-actions)
            $(document).on('click', '.btn-edit-role, .btn-edit-permission',
                function() { // dukung dua kelas kalau partial dipakai bersama
                    openModal('edit', {
                        update_url: $(this).data('update-url'),
                        name: $(this).data('name'),
                        group_name: $(this).data('groupName') || '' // <— ambil dari data-group-name
                    });
                });

            // Submit Create/Update
            $form.on('submit', function(e) {
                e.preventDefault();
                clearErrors();
                const mode = $form.data('mode');
                const action = $form.data('action');
                const data = {
                    name: $name.val(),
                    group_name: $group.val() || null
                };
                const payload = (mode === 'edit') ? $.extend({
                    _method: 'PUT'
                }, data) : data;

                $btnSave.prop('disabled', true).text('Saving...');
                $.post(action, payload)
                    .done(function(res) {
                        bsModal.hide();
                        toastOk(res.message || (mode === 'edit' ? 'Permission updated' :
                            'Permission created'));
                        reloadTable();
                        if (mode === 'create') $form[0].reset();
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422) {
                            const errs = xhr.responseJSON?.errors || {};
                            if (errs.name?.length) {
                                $name.addClass('is-invalid');
                                $nameErr.text(errs.name[0]);
                            }
                            if (errs.group_name?.length) {
                                $group.addClass('is-invalid');
                                $groupErr.text(errs.group_name[0]);
                            }
                            return;
                        }
                        toastErr(xhr.responseJSON?.message);
                    })
                    .always(function() {
                        $btnSave.prop('disabled', false).text('Save');
                    });
            });

            // Delete (delegated + Swal)
            $(document).on('click', '.btn-delete-role, .btn-delete-permission', function() {
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
                            toastOk(res.message || ('Permission ' + name + ' deleted'));
                            reloadTable();
                        })
                        .fail(function(xhr) {
                            toastErr(xhr.responseJSON?.message || 'Failed to delete');
                        });
                });
            });

            // Search manual (opsional)
            $('#perm-search-btn').on('click', function(e) {
                e.preventDefault();
                reloadTable();
            });
            $('#perm-search').on('keyup', function() {
                $(DT_SEL).DataTable().search(this.value).draw();
            });

            modalEl.addEventListener('hidden.bs.modal', clearErrors);

        })(jQuery);
    </script>
@endpush
