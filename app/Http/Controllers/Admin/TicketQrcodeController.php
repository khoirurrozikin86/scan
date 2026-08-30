<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\{
    TicketQrcodeStoreRequest,
    TicketQrcodeUpdateRequest,
    TicketQrcodeImportRequest
};

use App\Domain\TicketQrcodes\Queries\TicketQrcodeTableQuery;
use App\Domain\TicketQrcodes\Services\TicketQrcodeService;

use App\Imports\TicketQrcodesImport;
use App\Exports\TicketQrcodesExport;
use App\Models\TicketQrcode;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\Facades\DataTables;

class TicketQrcodeController extends Controller
{
    public function index()
    {
        return view(
            'super.ticket-qrcode.index'
        );
    }

    public function dt(
        TicketQrcodeTableQuery $q
    ) {
        return DataTables::eloquent(
            $q->builder()
        )

            ->editColumn(
                'created_at',
                fn(TicketQrcode $c) =>
                optional($c->created_at)
                    ->format('Y-m-d H:i')
            )

            ->editColumn(
                'updated_at',
                fn(TicketQrcode $c) =>
                optional($c->updated_at)
                    ->format('Y-m-d H:i')
            )

            ->addColumn(
                'actions',
                function (TicketQrcode $c) {

                    $actions = [

                        [
                            'type' => 'edit',
                            'label' => 'Edit',
                            'icon' => 'edit-2',

                            'update_url' => route(
                                'super.ticket-qrcode.update',
                                $c->getRouteKey()
                            ),

                            'payload' => [
                                'id' => $c->id,
                                'no_tiket' => $c->no_tiket,
                                'qrcode' => $c->qrcode,
                                'ticket_type' => $c->ticket_type,
                                'remark' => $c->remark,
                            ],
                        ],

                        [
                            'type' => 'delete',

                            'url' => route(
                                'super.ticket-qrcode.destroy',
                                $c->getRouteKey()
                            ),

                            'label' => 'Delete',
                            'icon' => 'trash-2',
                            'confirm' => "Delete Ticket {$c->no_tiket}?",
                            'disabled' => false,
                        ],

                    ];

                    return view(
                        'admin.partials.table-actions',
                        compact('actions')
                    )->render();
                }
            )

            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(
        TicketQrcodeStoreRequest $request,
        TicketQrcodeService $service
    ) {
        $ticketQrcode =
            $service->create(
                $request->sanitized()
            );

        return $request->ajax() ||
            $request->expectsJson()

            ? response()->json([
                'message' =>
                'Ticket QR Code created',

                'id' =>
                $ticketQrcode->id,
            ], 201)

            : back()->with(
                'success',
                'Ticket QR Code created'
            );
    }

    public function update(
        TicketQrcodeUpdateRequest $request,
        TicketQrcode $ticketQrcode,
        TicketQrcodeService $service
    ) {
        $service->update(
            $ticketQrcode,
            $request->sanitized()
        );

        return $request->ajax() ||
            $request->expectsJson()

            ? response()->json([
                'message' =>
                'Ticket QR Code updated',
            ])

            : back()->with(
                'success',
                'Ticket QR Code updated'
            );
    }

    public function destroy(
        TicketQrcode $ticketQrcode,
        TicketQrcodeService $service
    ) {
        $service->delete(
            $ticketQrcode
        );

        return request()->ajax() ||
            request()->expectsJson()

            ? response()->json([
                'message' =>
                'Ticket QR Code deleted',
            ])

            : redirect()
            ->route(
                'super.ticket-qrcode.index'
            )
            ->with(
                'success',
                'Ticket QR Code deleted'
            );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ]);


        try {

            Excel::import(
                new TicketQrcodesImport,
                $request->file('file')
            );


            return response()->json([
                'message' =>
                'Ticket QR Code imported successfully'
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function export()
    {
        return Excel::download(
            new TicketQrcodesExport,
            'ticket-qrcodes.xlsx'
        );
    }
}
