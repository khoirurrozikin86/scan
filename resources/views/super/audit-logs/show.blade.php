@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Audit Log Detail</h4>
                <p class="text-muted mb-0">
                    Detail aktivitas dan perubahan data
                </p>
            </div>

            <a href="{{ route('super.audit-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali
            </a>
        </div>

        {{-- Audit Information --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Informasi Audit
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Waktu</label>
                        <div>
                            {{ optional($auditLog->created_at)->format('d-m-Y H:i:s') }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">User</label>
                        <div>
                            {{ $auditLog->user?->name ?? 'System' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Module</label>
                        <div>
                            <span class="badge bg-primary">
                                {{ $auditLog->module }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Action</label>
                        <div>
                            <span class="badge bg-info text-dark">
                                {{ $auditLog->action }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $auditLog->description }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Object</label>
                        <div>
                            @if ($auditLog->auditable_type && $auditLog->auditable_id)
                                {{ class_basename($auditLog->auditable_type) }}
                                #{{ $auditLog->auditable_id }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Request Information --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-network-wired me-2"></i>
                    Request Information
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">IP Address</label>
                        <div>
                            {{ $auditLog->ip_address ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-bold">Method</label>
                        <div>
                            {{ $auditLog->method ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">URL</label>
                        <div class="text-break">
                            {{ $auditLog->url ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">User Agent</label>
                        <div class="border rounded p-2 bg-light text-break">
                            {{ $auditLog->user_agent ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Data Changes --}}
        <div class="row">

            {{-- Before --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Before
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        @if (!empty($auditLog->old_values))
                            <pre class="mb-0 p-3" style="max-height: 500px; overflow:auto;"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        @else
                            <div class="p-3 text-muted">
                                Tidak ada data perubahan sebelumnya.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- After --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            After
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        @if (!empty($auditLog->new_values))
                            <pre class="mb-0 p-3" style="max-height: 500px; overflow:auto;"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        @else
                            <div class="p-3 text-muted">
                                Tidak ada data perubahan setelahnya.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
