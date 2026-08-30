<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OutletStoreRequest;
use App\Http\Requests\Admin\OutletUpdateRequest;

use App\Domain\Outlets\Queries\OutletTableQuery;
use App\Domain\Outlets\Services\OutletService;

use App\Models\Outlet;

use Yajra\DataTables\Facades\DataTables;

use App\Exports\OutletsExport;
use Maatwebsite\Excel\Facades\Excel;

class OutletController extends Controller
{
    public function index()
    {
        return view('super.outlets.index');
    }

    public function dt(OutletTableQuery $q)
    {
        return DataTables::eloquent($q->builder())

            ->editColumn(
                'updated_at',
                fn(Outlet $outlet) => optional($outlet->updated_at)
                    ->format('Y-m-d H:i')
            )

            ->editColumn(
                'is_active',
                fn(Outlet $outlet) => $outlet->is_active
                    ? 'Active'
                    : 'Not Active'
            )

            ->editColumn(
                'is_camera_enabled',
                fn(Outlet $outlet) => $outlet->is_camera_enabled
                    ? 'Enabled'
                    : 'Disabled'
            )

            ->editColumn(
                'is_scanner_enabled',
                fn(Outlet $outlet) => $outlet->is_scanner_enabled
                    ? 'Enabled'
                    : 'Disabled'
            )

            ->addColumn('actions', function (Outlet $outlet) {

                $actions = [

                    // =========================
                    // EDIT
                    // =========================
                    [
                        'type'       => 'edit',
                        'label'      => 'Edit',
                        'icon'       => 'edit-2',

                        'update_url' => route(
                            'super.outlets.update',
                            $outlet->getRouteKey()
                        ),

                        'payload'    => [
                            'id'          => $outlet->id,
                            'outlet_code' => $outlet->outlet_code,
                            'outlet_name' => $outlet->outlet_name,
                            'outlet_type' => $outlet->outlet_type,
                            'is_active'   => $outlet->is_active,
                            'is_camera_enabled' => $outlet->is_camera_enabled,
                            'is_scanner_enabled' => $outlet->is_scanner_enabled,
                            'remark' => $outlet->remark,
                        ],
                    ],

                    // =========================
                    // DELETE
                    // =========================
                    [
                        'type'     => 'delete',

                        'url'      => route(
                            'super.outlets.destroy',
                            $outlet->getRouteKey()
                        ),

                        'label'    => 'Delete',
                        'icon'     => 'trash-2',

                        'confirm'  => "Delete Outlet {$outlet->outlet_name} ?",

                        'disabled' => false,
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(
        OutletStoreRequest $request,
        OutletService $service
    ) {
        $outlet = $service->create(
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Outlet created',
                'id'      => $outlet->id,
            ], 201)

            : back()->with(
                'success',
                'Outlet created'
            );
    }

    public function update(
        OutletUpdateRequest $request,
        Outlet $outlet,
        OutletService $service
    ) {
        $service->update(
            $outlet,
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Outlet updated',
            ])

            : back()->with(
                'success',
                'Outlet updated'
            );
    }

    public function destroy(
        Outlet $outlet,
        OutletService $service
    ) {
        $service->delete($outlet);

        return request()->ajax() || request()->expectsJson()
            ? response()->json([
                'message' => 'Outlet deleted',
            ])

            : redirect()
            ->route('super.outlets.index')
            ->with(
                'success',
                'Outlet deleted'
            );
    }


    public function export()
    {
        return Excel::download(
            new OutletsExport(),
            'outlets-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
