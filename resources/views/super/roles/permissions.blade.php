@extends('layouts.admin')
@section('title', 'Permissions — ' . $role->name)

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Access</a></li>
            <li class="breadcrumb-item"><a href="{{ route('super.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item"><a href="{{ route('super.roles.edit', $role) }}">{{ $role->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permissions</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-10 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">Permissions for: <span class="text-primary">{{ $role->name }}</span>
                        </h6>
                        <a href="{{ route('super.roles.index') }}" class="btn btn-light btn-sm">Back</a>
                    </div>

                    {{-- Alerts --}}
                    @if (session('ok'))
                        <div class="alert alert-success" role="alert">{{ session('ok') }}</div>
                    @endif

                    <form method="POST" action="{{ route('super.roles.permissions.update', $role) }}">
                        @csrf
                        @method('PUT')

                        {{-- Toolbar kecil: search & (de)select all --}}
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <input id="perm-search" type="search" class="form-control form-control-sm"
                                    placeholder="Search permissions...">
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" id="btn-select-all" class="btn btn-outline-secondary">Select
                                        all</button>
                                    <button type="button" id="btn-unselect-all" class="btn btn-outline-secondary">Unselect
                                        all</button>
                                    <button type="button" id="btn-clear-all"
                                        class="btn btn-outline-secondary">Clear</button>
                                </div>
                            </div>
                        </div>

                        {{-- Grid grouped permissions --}}
                        <div class="accordion" id="permAccordion">
                            @foreach ($permissions->groupBy('group_name') as $group => $perms)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $group }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $group }}" aria-expanded="false"
                                            aria-controls="collapse-{{ $group }}">
                                            {{ ucfirst($group) }}
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $group }}" class="accordion-collapse collapse show"
                                        aria-labelledby="heading-{{ $group }}" data-bs-parent="#permAccordion">
                                        <div class="accordion-body">
                                            <div class="list-group list-group-flush">
                                                @foreach ($perms as $p)
                                                    <label
                                                        class="list-group-item d-flex align-items-center gap-2 py-2 perm-item">
                                                        <input class="form-check-input me-2 perm-check" type="checkbox"
                                                            name="permissions[]" value="{{ $p->name }}"
                                                            @checked(in_array($p->name, $assigned))>
                                                        <span class="small perm-label">{{ $p->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('super.roles.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Sync</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const q = document.getElementById('perm-search');
            const items = Array.from(document.querySelectorAll('.perm-item'));
            const btnAll = document.getElementById('btn-select-all');
            const btnUnselect = document.getElementById('btn-unselect-all');
            const btnClear = document.getElementById('btn-clear-all');

            if (q) {
                q.addEventListener('input', function() {
                    const needle = this.value.trim().toLowerCase();
                    items.forEach(el => {
                        const text = el.querySelector('.perm-label')?.textContent?.toLowerCase() ??
                            '';
                        el.style.display = text.includes(needle) ? '' : 'none';
                    });
                });
            }

            if (btnAll) {
                btnAll.addEventListener('click', () => {
                    document.querySelectorAll('.perm-check').forEach(c => c.checked = true);
                });
            }

            if (btnUnselect) {
                btnUnselect.addEventListener('click', () => {
                    document.querySelectorAll('.perm-check').forEach(c => c.checked = false);
                });
            }

            // opsional: clear = kosongkan input search
            if (btnClear) {
                btnClear.addEventListener('click', () => {
                    q.value = '';
                    items.forEach(el => el.style.display = '');
                });
            }
        });
    </script>
@endpush
