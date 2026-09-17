<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Audit\Queries\AuditLogTableQuery;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Exports\AuditLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends Controller
{
    /**
     * Halaman Audit Log.
     */
    public function index()
    {
        return view('super.audit-logs.index');
    }

    /**
     * DataTables Audit Log.
     */
    public function dt(
        Request $request,
        AuditLogTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        if ($request->filled('date_from')) {
            $builder->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $builder->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        if ($request->filled('module')) {
            $builder->where(
                'module',
                strtoupper($request->module)
            );
        }

        if ($request->filled('action')) {
            $builder->where(
                'action',
                strtoupper($request->action)
            );
        }

        return DataTables::eloquent($builder)
            ->addColumn(
                'user_name',
                fn(AuditLog $auditLog) =>
                $auditLog->user?->name ?? 'System'
            )

            ->editColumn(
                'created_at',
                fn(AuditLog $auditLog) =>
                optional($auditLog->created_at)
                    ->format('Y-m-d H:i:s')
            )

            ->addColumn('object', function (AuditLog $auditLog) {

                if (
                    !$auditLog->auditable_type ||
                    !$auditLog->auditable_id
                ) {
                    return '-';
                }

                return class_basename(
                    $auditLog->auditable_type
                ) . ' #' . $auditLog->auditable_id;
            })

            ->addColumn('actions', function (AuditLog $auditLog) {

                return view(
                    'admin.partials.table-actions',
                    [
                        'actions' => [
                            [
                                'type' => 'link',
                                'label' => 'Detail',
                                'icon' => 'eye',
                                'url' => route(
                                    'super.audit-logs.show',
                                    $auditLog
                                ),
                            ],
                        ],
                    ]
                )->render();
            })

            ->rawColumns(['actions'])
            ->toJson();
    }

    /**
     * Detail Audit Log.
     */
    public function show(
        AuditLog $auditLog
    ) {
        $auditLog->load([
            'user:id,name',
            'auditable',
        ]);

        return view(
            'super.audit-logs.show',
            compact('auditLog')
        );
    }




    public function export(Request $request)
    {
        $dateFrom = $request->date_from
            ?: now()->format('Y-m-d');

        $dateTo = $request->date_to
            ?: now()->format('Y-m-d');

        $request->merge([
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        $filename = 'audit-log-' .
            $dateFrom .
            '-sd-' .
            $dateTo .
            '.xlsx';

        return Excel::download(
            new AuditLogsExport($request),
            $filename
        );
    }
}
