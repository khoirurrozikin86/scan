<?php

namespace App\Domain\Audit\Queries;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;

class AuditLogTableQuery
{
    public function builder(): Builder
    {
        return AuditLog::query()
            ->with([
                'user:id,name',
            ])
            ->select([
                'id',
                'user_id',
                'action',
                'module',
                'description',
                'auditable_type',
                'auditable_id',
                'ip_address',
                'method',
                'url',
                'created_at',
            ])
            ->orderByDesc('created_at');
    }
}
