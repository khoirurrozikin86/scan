<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Audit\Services\AuditLogService;
use App\Models\Outlet;
use App\Models\User;
use App\Models\TicketQrcode;
use App\Models\ScanRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\ScanRecordsExport;

class ScanController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLog
    ) {}

    /**
     * Camera Scan
     */
    public function camera(Request $request)
    {
        $user = $request->user();

        $outlets = $user->outlets()
            ->where('outlets.is_active', true)
            ->where('outlets.is_camera_enabled', true)
            ->orderBy('outlet_name')
            ->get([
                'outlets.id',
                'outlets.outlet_code',
                'outlets.outlet_name',
                'outlets.outlet_type',
                'outlets.scan_limit',
            ]);

        return view(
            'super.scan.camera',
            compact('outlets')
        );
    }

    /**
     * Barcode Scanner
     */
    public function scanner(Request $request)
    {
        $user = $request->user();

        $outlets = $user->outlets()
            ->where('outlets.is_active', true)
            ->where('outlets.is_scanner_enabled', true)
            ->orderBy('outlet_name')
            ->get([
                'outlets.id',
                'outlets.outlet_code',
                'outlets.outlet_name',
                'outlets.outlet_type',
                'outlets.scan_limit',
            ]);

        return view(
            'super.scan.scanner',
            compact('outlets')
        );
    }

    /**
     * Scan Ticket
     */
    public function scan(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI REQUEST
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'outlet_id' => [
                'required',
                'integer',
            ],

            'qrcode' => [
                'required',
                'string',
                'max:255',
            ],

            'scan_method' => [
                'required',
                'in:camera,scanner',
            ],
        ]);

        $user = $request->user();

        $outletId = (int) $request->input('outlet_id');
        $scanMethod = $request->input('scan_method');
        $qrcode = trim((string) $request->input('qrcode'));

        /*
        |--------------------------------------------------------------------------
        | CEK OUTLET + AKSES USER
        |--------------------------------------------------------------------------
        |
        | User hanya boleh scan pada outlet yang memang diberikan kepadanya.
        | Outlet juga harus aktif.
        |
        */
        $outlet = $user->outlets()
            ->where('outlets.id', $outletId)
            ->where('outlets.is_active', true)
            ->first();

        if (!$outlet) {

            $this->auditLog->log(
                action: 'SCAN_FAILED',
                module: 'SCAN_RECORD',
                description: "Scan gagal: user #{$user->id} tidak memiliki akses ke outlet #{$outletId}",
                newValues: [
                    'outlet_id' => $outletId,
                    'qrcode' => $qrcode,
                    'scan_method' => $scanMethod,
                    'reason' => 'OUTLET_ACCESS_DENIED',
                ],
            );

            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke outlet ini.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK FITUR SCANNER
        |--------------------------------------------------------------------------
        */
        if (
            $scanMethod === 'scanner'
            && !$outlet->is_scanner_enabled
        ) {

            $this->auditLog->log(
                action: 'SCAN_FAILED',
                module: 'SCAN_RECORD',
                description: "Scan gagal: barcode scanner tidak aktif di outlet {$outlet->outlet_name}",
                model: $outlet,
                newValues: [
                    'outlet_id' => $outlet->id,
                    'qrcode' => $qrcode,
                    'scan_method' => $scanMethod,
                    'reason' => 'SCANNER_DISABLED',
                ],
            );

            return response()->json([
                'success' => false,
                'message' => 'Barcode scanner tidak diaktifkan pada outlet ini.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK CAMERA
        |--------------------------------------------------------------------------
        */
        if (
            $scanMethod === 'camera'
            && !$outlet->is_camera_enabled
        ) {

            $this->auditLog->log(
                action: 'SCAN_FAILED',
                module: 'SCAN_RECORD',
                description: "Scan gagal: camera scanner tidak aktif di outlet {$outlet->outlet_name}",
                model: $outlet,
                newValues: [
                    'outlet_id' => $outlet->id,
                    'qrcode' => $qrcode,
                    'scan_method' => $scanMethod,
                    'reason' => 'CAMERA_DISABLED',
                ],
            );

            return response()->json([
                'success' => false,
                'message' => 'Camera scanner tidak diaktifkan pada outlet ini.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | CARI TICKET QR CODE
        |--------------------------------------------------------------------------
        |
        | QR harus benar-benar terdaftar di tabel ticket_qrcodes.
        |
        */
        $ticket = TicketQrcode::query()
            ->where('qrcode', $qrcode)
            ->first();

        if (!$ticket) {

            $this->auditLog->log(
                action: 'SCAN_FAILED',
                module: 'SCAN_RECORD',
                description: "Scan gagal: QR Code {$qrcode} tidak ditemukan",
                model: $outlet,
                newValues: [
                    'outlet_id' => $outlet->id,
                    'outlet_name' => $outlet->outlet_name,
                    'qrcode' => $qrcode,
                    'scan_method' => $scanMethod,
                    'reason' => 'TICKET_QRCODE_NOT_FOUND',
                ],
            );

            return response()->json([
                'success' => false,
                'message' => 'QR Code tiket tidak terdaftar.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK SCAN LIMIT
        |--------------------------------------------------------------------------
        |
        | scan_limit:
        |
        | 1    = maksimal 1 scan
        | 2    = maksimal 2 scan
        | 3    = maksimal 3 scan
        | NULL = unlimited
        |
        */
        $scanCount = ScanRecord::query()
            ->where('ticket_qrcode_id', $ticket->id)
            ->where('outlet_id', $outlet->id)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | BATAS SCAN TERCAPAI
        |--------------------------------------------------------------------------
        */
        if (
            $outlet->scan_limit !== null
            && $scanCount >= $outlet->scan_limit
        ) {

            $this->auditLog->log(
                action: 'SCAN_FAILED',
                module: 'SCAN_RECORD',
                description: "Scan gagal: tiket {$ticket->no_tiket} telah mencapai batas {$outlet->scan_limit}x di outlet {$outlet->outlet_name}",
                model: $ticket,
                newValues: [
                    'outlet_id' => $outlet->id,
                    'outlet_name' => $outlet->outlet_name,
                    'outlet_type' => $outlet->outlet_type,

                    'ticket_qrcode_id' => $ticket->id,
                    'qrcode' => $ticket->qrcode,
                    'no_tiket' => $ticket->no_tiket,

                    'scan_method' => $scanMethod,

                    'scan_count' => $scanCount,
                    'scan_limit' => $outlet->scan_limit,

                    'reason' => 'SCAN_LIMIT_REACHED',
                ],
            );

            return response()->json([
                'success' => false,
                'message' => "Tiket sudah mencapai batas scan {$outlet->scan_limit}x di wahana ini.",
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SCAN
        |--------------------------------------------------------------------------
        */
        $scanRecord = DB::transaction(function () use (
            $user,
            $outlet,
            $ticket,
            $scanMethod,
            $scanCount
        ) {

            $scanRecord = ScanRecord::create([
                'user_id' => $user->id,
                'outlet_id' => $outlet->id,
                'ticket_qrcode_id' => $ticket->id,

                'qrcode' => $ticket->qrcode,
                'no_tiket' => $ticket->no_tiket,
                'ticket_type' => $ticket->ticket_type,

                'scan_method' => $scanMethod,
                'scanned_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | AUDIT SUCCESS
            |--------------------------------------------------------------------------
            */
            $this->auditLog->log(
                action: 'SCAN',
                module: 'SCAN_RECORD',
                description: "Scan tiket {$ticket->no_tiket} berhasil di outlet {$outlet->outlet_name}",
                model: $scanRecord,
                newValues: [
                    'scan_record_id' => $scanRecord->id,

                    'ticket_qrcode_id' => $ticket->id,
                    'qrcode' => $ticket->qrcode,
                    'no_tiket' => $ticket->no_tiket,
                    'ticket_type' => $ticket->ticket_type,

                    'outlet_id' => $outlet->id,
                    'outlet_name' => $outlet->outlet_name,
                    'outlet_type' => $outlet->outlet_type,

                    'scan_count_before' => $scanCount,
                    'scan_count_after' => $scanCount + 1,

                    'scan_limit' => $outlet->scan_limit,

                    'scan_policy' => $outlet->scan_limit === null
                        ? 'UNLIMITED'
                        : 'LIMITED',

                    'scan_method' => $scanMethod,

                    'scanned_at' => $scanRecord->scanned_at
                        ?->format('Y-m-d H:i:s'),
                ],
            );

            return $scanRecord;
        });

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        $currentScanCount = $scanCount + 1;

        $remainingScan = $outlet->scan_limit === null
            ? null
            : max(
                0,
                $outlet->scan_limit - $currentScanCount
            );

        return response()->json([
            'success' => true,

            'message' => 'Tiket berhasil diterima.',

            'data' => [
                'qrcode' => $ticket->qrcode,
                'no_tiket' => $ticket->no_tiket,
                'ticket_type' => $ticket->ticket_type,

                'outlet_code' => $outlet->outlet_code,
                'outlet_name' => $outlet->outlet_name,
                'outlet_type' => $outlet->outlet_type,

                'scan_method' => $scanMethod,

                'scan_count' => $currentScanCount,
                'scan_limit' => $outlet->scan_limit,
                'remaining_scan' => $remainingScan,

                'scanned_at' => $scanRecord->scanned_at
                    ->format('d-m-Y H:i:s'),
            ],
        ]);
    }

    /**
     * Scan Records
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $isSuperAdmin = $user->hasRole('super-admin');

        /*
        |--------------------------------------------------------------------------
        | OUTLET YANG BOLEH DIAKSES USER
        |--------------------------------------------------------------------------
        */
        if ($isSuperAdmin) {

            $outlets = Outlet::query()
                ->where('is_active', true)
                ->orderBy('outlet_name')
                ->get([
                    'id',
                    'outlet_code',
                    'outlet_name',
                    'outlet_type',
                    'scan_limit',
                ]);
        } else {

            $outlets = $user->outlets()
                ->where('outlets.is_active', true)
                ->orderBy('outlet_name')
                ->get([
                    'outlets.id',
                    'outlets.outlet_code',
                    'outlets.outlet_name',
                    'outlets.outlet_type',
                    'outlets.scan_limit',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | USERNAME / OPERATOR
        |--------------------------------------------------------------------------
        */
        $users = User::query()
            ->whereHas('scanRecords', function ($query) use (
                $isSuperAdmin,
                $user
            ) {

                if (!$isSuperAdmin) {

                    $query->whereIn(
                        'outlet_id',
                        $user->outlets()->pluck('outlets.id')
                    );
                }
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | OUTLET TYPE
        |--------------------------------------------------------------------------
        */
        $outletTypes = $outlets
            ->pluck('outlet_type')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view(
            'super.scan.records',
            compact(
                'users',
                'outlets',
                'outletTypes'
            )
        );
    }

    /**
     * DataTable Scan Records
     */
    public function dt(Request $request)
    {
        $user = $request->user();

        $isSuperAdmin = $user->hasRole('super-admin');

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR
        |--------------------------------------------------------------------------
        */
        $query = ScanRecord::query()
            ->with([
                'user',
                'outlet',
                'ticketQrcode',
            ]);

        /*
        |--------------------------------------------------------------------------
        | BATASI OUTLET USER
        |--------------------------------------------------------------------------
        */
        if (!$isSuperAdmin) {

            $allowedOutletIds = $user->outlets()
                ->pluck('outlets.id');

            $query->whereIn(
                'outlet_id',
                $allowedOutletIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_from')) {

            $query->whereDate(
                'scanned_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'scanned_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER USERNAME / OPERATOR
        |--------------------------------------------------------------------------
        */
        if ($request->filled('user_id')) {

            $query->where(
                'user_id',
                $request->user_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER OUTLET
        |--------------------------------------------------------------------------
        */
        if ($request->filled('outlet_id')) {

            $query->where(
                'outlet_id',
                $request->outlet_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER OUTLET TYPE
        |--------------------------------------------------------------------------
        */
        if ($request->filled('outlet_type')) {

            $query->whereHas('outlet', function ($q) use ($request) {

                $q->where(
                    'outlet_type',
                    $request->outlet_type
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | REKAP TIKET UNIK PER OUTLET
        |
        | 1 QR + 1 OUTLET = 1 tiket.
        | Duplicate scan tetap tampil di tabel detail,
        | tetapi hanya dihitung 1 pada tabel rekap.
        |--------------------------------------------------------------------------
        */
        $summaryQuery = clone $query;

        $outletSummary = $summaryQuery
            ->select(
                'outlet_id',
                DB::raw('COUNT(DISTINCT ticket_qrcode_id) as total_tiket')
            )
            ->with(['outlet:id,outlet_code,outlet_name'])
            ->groupBy('outlet_id')
            ->orderByDesc('total_tiket')
            ->get()
            ->map(function ($row) {
                return [
                    'outlet_id' => $row->outlet_id,
                    'outlet_code' => $row->outlet?->outlet_code ?? '-',
                    'outlet_name' => $row->outlet?->outlet_name ?? '-',
                    'total_tiket' => (int) $row->total_tiket,
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | DATATABLE
        |--------------------------------------------------------------------------
        */
        return DataTables::of($query)

            ->addColumn(
                'user_name',
                fn($scan) =>
                $scan->user?->name ?? '-'
            )

            ->addColumn(
                'outlet_name',
                fn($scan) =>
                $scan->outlet?->outlet_name ?? '-'
            )

            ->addColumn(
                'outlet_type',
                fn($scan) =>
                $scan->outlet?->outlet_type ?? '-'
            )

            ->addColumn(
                'scan_limit',
                function ($scan) {

                    $limit = $scan->outlet?->scan_limit;

                    return $limit === null
                        ? 'Unlimited'
                        : $limit . ' kali';
                }
            )

            ->editColumn(
                'scan_method',
                fn($scan) =>
                ucfirst($scan->scan_method)
            )

            ->editColumn(
                'scanned_at',
                fn($scan) =>
                $scan->scanned_at
                    ? $scan->scanned_at->format('d-m-Y H:i:s')
                    : '-'
            )

            ->addColumn('action', function ($scan) {

                $url = route(
                    'super.scan-records.destroy',
                    [
                        'scanRecord' => $scan->id,
                    ]
                );

                return '
                    <span
                        class="badge bg-danger btn-delete-scan"
                        data-url="' . e($url) . '"
                        title="Hapus"
                        style="
                            cursor:pointer;
                            display:inline-flex;
                            align-items:center;
                            justify-content:center;
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                            <path d="M10 11v6"></path>
                            <path d="M14 11v6"></path>
                            <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </span>
                ';
            })

            ->rawColumns([
                'action',
            ])

            ->with('outlet_summary', $outletSummary)

            ->make(true);
    }

    /**
     * Scan History
     */
    public function history(Request $request)
    {
        $user = $request->user();

        $outletId = $request->input('outlet_id');

        if (!$outletId) {

            return response()->json([
                'data' => [],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK AKSES OUTLET
        |--------------------------------------------------------------------------
        */
        $isSuperAdmin = $user->hasRole('super-admin');

        if ($isSuperAdmin) {

            $hasAccess = Outlet::query()
                ->whereKey($outletId)
                ->where('is_active', true)
                ->exists();
        } else {

            $hasAccess = $user->outlets()
                ->where('outlets.id', $outletId)
                ->where('outlets.is_active', true)
                ->exists();
        }

        if (!$hasAccess) {

            return response()->json([
                'message' => 'Anda tidak memiliki akses ke outlet ini.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL 10 HISTORY TERBARU
        |--------------------------------------------------------------------------
        */
        $records = ScanRecord::query()
            ->where('outlet_id', $outletId)
            ->latest('scanned_at')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([

            'data' => $records->map(function ($record) {

                return [

                    'qrcode' => $record->qrcode,

                    'no_tiket' => $record->no_tiket,

                    'ticket_type' => $record->ticket_type,

                    'scan_method' => $record->scan_method,

                    'scanned_at' => $record->scanned_at
                        ? $record->scanned_at->format(
                            'd-m-Y H:i:s'
                        )
                        : '-',

                ];
            }),

        ]);
    }

    /**
     * Delete Scan Record
     */
    public function destroy(ScanRecord $scanRecord)
    {
        $oldValues = $scanRecord->toArray();

        $scanRecordId = $scanRecord->id;
        $ticketNumber = $scanRecord->no_tiket;
        $outletName = $scanRecord->outlet?->outlet_name ?? '-';

        DB::transaction(function () use (
            $scanRecord,
            $oldValues,
            $scanRecordId,
            $ticketNumber,
            $outletName
        ) {

            $scanRecord->delete();

            /*
            |--------------------------------------------------------------------------
            | AUDIT DELETE
            |--------------------------------------------------------------------------
            */
            $this->auditLog->log(
                action: 'DELETE',
                module: 'SCAN_RECORD',
                description: "Menghapus Scan Record #{$scanRecordId} tiket {$ticketNumber} dari outlet {$outletName}",
                model: $scanRecord,
                oldValues: $oldValues,
            );
        });

        return response()->json([
            'message' => 'Data scan berhasil dihapus.',
        ]);
    }

    /**
     * Export Scan Records
     */
    public function export(Request $request)
    {
        $request->validate([
            'date_from' => [
                'required',
                'date',
            ],

            'date_to' => [
                'required',
                'date',
                'after_or_equal:date_from',
            ],

            'user_id' => [
                'nullable',
                'integer',
            ],

            'outlet_id' => [
                'nullable',
                'integer',
            ],

            'outlet_type' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $user = $request->user();

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $userId = $request->filled('user_id')
            ? (int) $request->input('user_id')
            : null;

        $outletId = $request->filled('outlet_id')
            ? (int) $request->input('outlet_id')
            : null;

        $outletType = $request->filled('outlet_type')
            ? $request->input('outlet_type')
            : null;

        $isSuperAdmin = $user->hasRole('super-admin');

        /*
        |--------------------------------------------------------------------------
        | OUTLET ACCESS
        |--------------------------------------------------------------------------
        */
        $allowedOutletIds = [];

        if (!$isSuperAdmin) {

            $allowedOutletIds = $user->outlets()
                ->where('outlets.is_active', true)
                ->pluck('outlets.id')
                ->map(fn($id) => (int) $id)
                ->all();

            /*
            |--------------------------------------------------------------------------
            | USER MEMILIH OUTLET DI LUAR HAK AKSES
            |--------------------------------------------------------------------------
            */
            if (
                $outletId !== null
                && !in_array(
                    $outletId,
                    $allowedOutletIds,
                    true
                )
            ) {

                abort(
                    403,
                    'Anda tidak memiliki akses ke outlet ini.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | USER ACCESS
        |--------------------------------------------------------------------------
        */
        if ($userId !== null) {

            $userExists = User::query()
                ->whereKey($userId)
                ->whereHas('scanRecords', function ($query) use (
                    $isSuperAdmin,
                    $allowedOutletIds
                ) {

                    if (!$isSuperAdmin) {

                        $query->whereIn(
                            'outlet_id',
                            $allowedOutletIds
                        );
                    }
                })
                ->exists();

            if (!$userExists) {

                abort(
                    403,
                    'User tidak memiliki data scan yang dapat diakses.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILENAME
        |--------------------------------------------------------------------------
        */
        $filename = 'scan-records_'
            . $dateFrom
            . '_sd_'
            . $dateTo;

        if ($userId !== null) {

            $filename .= '_user-' . $userId;
        }

        if ($outletId !== null) {

            $filename .= '_outlet-' . $outletId;
        }

        $filename .= '.xlsx';

        /*
        |--------------------------------------------------------------------------
        | AUDIT EXPORT
        |--------------------------------------------------------------------------
        */
        $this->auditLog->log(
            action: 'EXPORT',
            module: 'SCAN_RECORD',
            description: "Export Scan Record periode {$dateFrom} s/d {$dateTo}",
            newValues: [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'user_id' => $userId,
                'outlet_id' => $outletId,
                'outlet_type' => $outletType,
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | EXPORT
        |--------------------------------------------------------------------------
        */
        return Excel::download(
            new ScanRecordsExport(
                $dateFrom,
                $dateTo,
                $userId,
                $outletId,
                $outletType,
                $allowedOutletIds
            ),
            $filename
        );
    }
}
