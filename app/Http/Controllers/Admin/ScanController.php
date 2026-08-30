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
    public function dt(
        ScanRecordTableQuery $q
    ) {
        return DataTables::eloquent(
            $q->builder()
        )

            ->addColumn(
                'user_name',
                fn($scan) =>
                $scan->user?->name ?? '-'
            )

            ->addColumn(
                'outlet_name',
                fn($scan) =>
                $scan->outlet
                    ? $scan->outlet->outlet_code
                    . ' - '
                    . $scan->outlet->outlet_name
                    : '-'
            )

            ->editColumn(
                'scanned_at',
                fn($scan) =>
                optional($scan->scanned_at)
                    ->format('Y-m-d H:i:s')
            )

            ->editColumn(
                'scan_method',
                fn($scan) =>
                ucfirst($scan->scan_method)
            )

            ->rawColumns([
                'user_name',
                'outlet_name',
            ])

            ->toJson();
    }



    public function history(Request $request)
    {
        $request->validate([
            'outlet_id' => [
                'required',
                'integer',
            ],
        ]);

        $user = auth()->user();


        /*
    |--------------------------------------------------------------------------
    | CEK AKSES OUTLET
    |--------------------------------------------------------------------------
    */

        $outlet = $user->outlets()
            ->where('outlets.id', $request->outlet_id)
            ->where('outlets.is_active', true)
            ->where('outlets.is_scanner_enabled', true)
            ->first();


        if (!$outlet) {

            return response()->json([
                'message' => 'Anda tidak memiliki akses ke outlet ini.',
            ], 403);
        }


        /*
    |--------------------------------------------------------------------------
    | 10 SCAN TERBARU
    |--------------------------------------------------------------------------
    */

        $records = ScanRecord::query()
            ->with('ticketQrcode')
            ->where('outlet_id', $outlet->id)
            ->latest('scanned_at')
            ->limit(10)
            ->get();


        return response()->json([

            'data' => $records->map(function ($record) {

                return [

                    'qrcode' => $record->qrcode,

                    'no_tiket' =>
                    $record->ticketQrcode?->no_tiket,

                    'scanned_at' =>
                    $record->scanned_at
                        ? $record->scanned_at->format(
                            'd-m-Y H:i:s'
                        )
                        : '-',

                ];
            }),

        ]);
    }
}
