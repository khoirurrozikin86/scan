<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\TicketStoreRequest;
use App\Http\Requests\Admin\TicketUpdateRequest;

use Illuminate\Http\Request;

use App\Domain\Tickets\Queries\TicketTableQuery;
use App\Domain\Tickets\Services\TicketService;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;

use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function index()
    {

        return view('super.tickets.index', [
            'categories' => Category::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function dt(TicketTableQuery $q)
    {
        return DataTables::eloquent($q->builder())

            ->addColumn(
                'category',
                fn(Ticket $t) =>
                $t->category?->name
            )

            ->addColumn(
                'assigned_user',
                fn(Ticket $t) =>
                $t->assignedUser?->name ?? '-'
            )

            ->addColumn(
                'created_by_name',
                fn(Ticket $t) =>
                $t->creator?->name ?? '-'
            )

            ->editColumn('priority', function (Ticket $t) {

                $badge = match ($t->priority) {
                    'High' => 'danger',
                    'Medium' => 'warning',
                    default => 'success',
                };

                return "<span class='badge bg-{$badge}'>{$t->priority}</span>";
            })

            ->editColumn('status', function (Ticket $t) {

                $badge = match ($t->status) {
                    'Open' => 'primary',
                    'In Progress' => 'warning',
                    'Resolved' => 'success',
                    'Closed' => 'secondary',
                    default => 'secondary',
                };

                return "<span class='badge bg-{$badge}'>{$t->status}</span>";
            })

            ->editColumn('created_date', function (Ticket $t) {

                return $t->created_date;
            })


            // TAMBAHKAN DI SINI
            ->addColumn('progress', function (Ticket $t) {

                if (!auth()->user()->can('tickets.update-status')) {
                    return '-';
                }

                $statuses = [
                    'Open',
                    'In Progress',
                    'Resolved',
                    'Closed'
                ];

                $html = '<select
        class="form-select form-select-sm ticket-status"
        data-id="' . $t->id . '">';

                foreach ($statuses as $status) {

                    $selected =
                        $t->status === $status
                        ? 'selected'
                        : '';

                    $html .=
                        '<option value="' . $status . '" ' . $selected . '>'
                        . $status .
                        '</option>';
                }

                $html .= '</select>';

                return $html;
            })



            ->addColumn('actions', function (Ticket $t) {

                $actions = [

                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',

                        'update_url' => route(
                            'super.tickets.update',
                            $t
                        ),

                        'payload' => [
                            'title' => $t->title,
                            'category_id' => $t->category_id,
                            'priority' => $t->priority,
                            'status' => $t->status,
                            'assigned_user_id' => $t->assigned_user_id,
                            'description' => $t->description,
                            'notes' => $t->notes,
                        ],
                    ],

                    [
                        'type' => 'delete',

                        'url' => route(
                            'super.tickets.destroy',
                            $t
                        ),

                        'label' => 'Delete',
                        'icon' => 'trash-2',

                        'confirm' =>
                        "Delete ticket {$t->ticket_number} ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns([
                'priority',
                'status',
                'progress',
                'actions'
            ])

            ->toJson();
    }

    public function store(
        TicketStoreRequest $request,
        TicketService $service
    ) {
        $ticket = $service->create(
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()

            ? response()->json([
                'message' => 'Ticket created',
                'id' => $ticket->id,
            ], 201)

            : back()->with(
                'success',
                'Ticket created'
            );
    }

    public function update(
        TicketUpdateRequest $request,
        Ticket $ticket,
        TicketService $service
    ) {

        $service->update(
            $ticket,
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()

            ? response()->json([
                'message' => 'Ticket updated',
            ])

            : back()->with(
                'success',
                'Ticket updated'
            );
    }

    public function destroy(
        Ticket $ticket,
        TicketService $service
    ) {

        $service->delete($ticket);

        return request()->ajax() || request()->expectsJson()

            ? response()->json([
                'message' => 'Ticket deleted',
            ])

            : redirect()
            ->route('super.tickets.index')
            ->with(
                'success',
                'Ticket deleted'
            );
    }


    public function updateStatus(
        Request $request,
        Ticket $ticket,
        TicketService $service
    ) {

        $request->validate([
            'status' => [
                'required',
                'in:Open,In Progress,Resolved,Closed'
            ]
        ]);

        $service->updateStatus(
            $ticket,
            $request->status
        );

        return response()->json([
            'message' => 'Status updated'
        ]);
    }
}
