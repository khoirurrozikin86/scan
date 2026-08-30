@extends('layouts.admin')
@section('title', $role->exists ? 'Edit Role' : 'New Role')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Access</a></li>
            <li class="breadcrumb-item"><a href="{{ route('super.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $role->exists ? 'Edit' : 'New' }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title mb-0">{{ $role->exists ? 'Edit Role' : 'New Role' }}</h6>
                        <a href="{{ route('super.roles.index') }}" class="btn btn-light btn-sm">Back</a>
                    </div>

                    {{-- Alert --}}
                    @if (session('ok'))
                        <div class="alert alert-success" role="alert">{{ session('ok') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST"
                        action="{{ $role->exists ? route('super.roles.update', $role) : route('super.roles.store') }}">
                        @csrf
                        @if ($role->exists)
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required
                                placeholder="e.g. admin, editor">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('super.roles.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
