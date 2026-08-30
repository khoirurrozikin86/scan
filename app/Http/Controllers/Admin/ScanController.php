<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\ScanRecords\Queries\ScanRecordTableQuery;
use App\Models\Outlet;
use App\Models\User;
use App\Models\TicketQrcode;
use App\Models\ScanRecord;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


use App\Exports\ScanRecordsExport;
use Maatwebsite\Excel\Facades\Excel;



class ScanController extends Controller
{
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

        $user = auth()->user();

        $scanMethod = $request->input('scan_method');

        /*
    |--------------------------------------------------------------------------
    | CEK OUTLET
    |--------------------------------------------------------------------------
    */

        $outlet = $user->outlets()
            ->where('outlets.id', $request->outlet_id)
            ->where('outlets.is_active', true)
            ->first();

        if (!$outlet) {
            return response()->json([
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
            return response()->json([
                'message' => 'Barcode scanner tidak diaktifkan pada outlet ini.',
            ], 403);
        }

        if (
            $scanMethod === 'camera'
            && !$outlet->is_camera_enabled
        ) {
            return response()->json([
                'message' => 'Camera scanner tidak diaktifkan pada outlet ini.',
            ], 403);
        }

        /*
    |--------------------------------------------------------------------------
    | CARI TIKET
    |--------------------------------------------------------------------------
    */

        $ticket = TicketQrcode::query()
            ->where('qrcode', trim($request->qrcode))
            ->first();


        if (!$ticket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        /*
    |--------------------------------------------------------------------------
    | CEK SUDAH PERNAH SCAN
    |--------------------------------------------------------------------------
    */

        $alreadyScanned = ScanRecord::query()
            ->where('ticket_qrcode_id', $ticket->id)
            ->where('outlet_id', $outlet->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'message' => 'Tiket sudah pernah digunakan di wahana ini.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

        $scanRecord = DB::transaction(function () use (
            $user,
            $outlet,
            $ticket,
            $scanMethod
        ) {

            return ScanRecord::create([

                'user_id' => $user->id,

                'outlet_id' => $outlet->id,

                'ticket_qrcode_id' => $ticket->id,

                'qrcode' => $ticket->qrcode,

                'no_tiket' => $ticket->no_tiket,
                'ticket_type' => $ticket->ticket_type,

                'scan_method' => $scanMethod,

                'scanned_at' => now(),

            ]);
        });

        /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

        return response()->json([

            'success' => true,

            'message' => 'Tiket berhasil diterima.',

            'data' => [

                'qrcode' => $ticket->qrcode,

                'no_tiket' => $ticket->no_tiket,

                'ticket_type' => $ticket->ticket_type,

                'outlet_code' => $outlet->outlet_code,

                'outlet_name' => $outlet->outlet_name,

                'scan_method' => $scanMethod,

                'scanned_at' => $scanRecord->scanned_at
                    ->format('d-m-Y H:i:s'),

            ],

        ]);
    }







    /**
     * Scan Records
     */
    public function index()
    {
        return view(
            'super.scan.records'
        );
    }


    /**
     * DataTable Scan Records
     */

    public function dt(Request $request)
    {
        $query = ScanRecord::query()
            ->with([
                'user',
                'outlet',
            ]);


        /*
    |--------------------------------------------------------------------------
    | FILTER DARI TANGGAL
    |--------------------------------------------------------------------------
    */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'scanned_at',
                '>=',
                $request->date_from
            );
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER SAMPAI TANGGAL
    |--------------------------------------------------------------------------
    */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'scanned_at',
                '<=',
                $request->date_to
            );
        }


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



            ->editColumn(
                'scan_method',
                fn($scan) =>
                ucfirst($scan->scan_method)
            )

            ->editColumn(
                'scanned_at',
                fn($scan) =>
                $scan->scanned_at
                    ? $scan->scanned_at->format(
                        'd-m-Y H:i:s'
                    )
                    : '-'
            )


            ->addColumn('action', function ($scan) {

                return '
        <span
            class="badge bg-danger btn-delete-scan"
            data-url="' . route(
                    'super.scan-records.destroy',
                    ['scanRecord' => $scan->id]
                ) . '"
            title="Hapus"
            style="cursor:pointer;">

            <i class="fas fa-trash">X</i>

        </span>
    ';
            })



            ->rawColumns([
                'action'
            ])

            ->make(true);
    }








    public function history(Request $request)
    {
        $user = auth()->user();

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
    | Super Admin boleh semua outlet
    */

        $isSuperAdmin = $user->hasRole('super-admin');

        if (!$isSuperAdmin) {

            $hasAccess = $user->outlets()
                ->where('outlets.id', $outletId)
                ->where('outlets.is_active', true)
                ->exists();

            if (!$hasAccess) {

                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke outlet ini.'
                ], 403);
            }
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



    public function destroy(ScanRecord $scanRecord)
    {
        $scanRecord->delete();

        return response()->json([
            'message' => 'Data scan berhasil dihapus.',
        ]);
    }






    public function export(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        if (!$dateFrom || !$dateTo) {

            return back()->with(
                'error',
                'Tanggal filter belum dipilih.'
            );
        }

        if ($dateFrom > $dateTo) {

            return back()->with(
                'error',
                'Range tanggal tidak valid.'
            );
        }

        $filename =
            'scan-records_' .
            $dateFrom .
            '_sd_' .
            $dateTo .
            '.xlsx';

        return Excel::download(
            new ScanRecordsExport(
                $dateFrom,
                $dateTo
            ),
            $filename
        );
    }
}
