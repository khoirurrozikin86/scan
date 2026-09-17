<?php

namespace App\Exports;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditLogsExport implements
    FromQuery,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected Request $request
    ) {}

    public function query()
    {
        $query = AuditLog::query()
            ->with('user:id,name')
            ->orderByDesc('created_at');

        if ($this->request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $this->request->date_from
            );
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $this->request->date_to
            );
        }

        if ($this->request->filled('module')) {
            $query->where(
                'module',
                strtoupper($this->request->module)
            );
        }

        if ($this->request->filled('action')) {
            $query->where(
                'action',
                strtoupper($this->request->action)
            );
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'User',
            'Module',
            'Action',
            'Object',
            'Description',
            'IP Address',
            'Method',
            'URL',
            'User Agent',
        ];
    }

    public function map($auditLog): array
    {
        return [
            optional($auditLog->created_at)
                ->format('Y-m-d H:i:s'),

            $auditLog->user?->name ?? 'System',

            $auditLog->module,

            $auditLog->action,

            $auditLog->auditable_type
                ? class_basename($auditLog->auditable_type)
                . ' #' . $auditLog->auditable_id
                : '-',

            $auditLog->description,

            $auditLog->ip_address,

            $auditLog->method,

            $auditLog->url,

            $auditLog->user_agent,
        ];
    }
}
