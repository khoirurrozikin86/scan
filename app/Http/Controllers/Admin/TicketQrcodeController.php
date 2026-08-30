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
use App\Models\TicketQrcode;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\Facades\DataTables;

class TicketQrcodeController extends Controller
{
    public function index()
    {
        return view(
            'super.ticket-qrcodes.index'
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
                                'super.ticket-qrcodes.update',
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
                                'super.ticket-qrcodes.destroy',
                                $c->getRouteKey()
                            ),

                            'label' => 'Delete',

                            'icon' => 'trash-2',

                            'confirm' =>
                            "Delete Ticket {$c->no_tiket}?",

                            'disabled' => false,
                        ],

                    ];

                    return view(
                        'admin.partials.table-actions',
                        compact('actions')
                    )->render();
                }
            )

            ->rawColumns([
                'actions'
            ])

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
                'super.ticket-qrcodes.index'
            )
            ->with(
                'success',
                'Ticket QR Code deleted'
            );
    }

    public function import(
        TicketQrcodeImportRequest $request
    ) {
        DB::transaction(function () use ($request) {

            Excel::import(
                new TicketQrcodesImport(),
                $request->file('file')
            );
        });

        return $request->ajax() ||
            $request->expectsJson()

            ? response()->json([
                'message' =>
                'Ticket QR Code imported successfully',
            ])

            : back()->with(
                'success',
                'Ticket QR Code imported successfully'
            );
    }
}
