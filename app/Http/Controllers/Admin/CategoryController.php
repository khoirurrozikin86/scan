<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;

use App\Domain\Categories\Queries\CategoryTableQuery;
use App\Domain\Categories\Services\CategoryService;

use App\Models\Category;

use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('super.categories.index');
    }

    public function dt(CategoryTableQuery $q)
    {
        return DataTables::eloquent($q->builder())
            ->editColumn(
                'updated_at',
                fn(Category $c) => optional($c->updated_at)
                    ->format('Y-m-d H:i')
            )

            ->addColumn('actions', function (Category $c) {

                $actions = [

                    [
                        'type'       => 'edit',
                        'label'      => 'Edit',
                        'icon'       => 'edit-2',
                        'update_url' => route(
                            'super.categories.update',
                            $c->getRouteKey()
                        ),
                        'payload'    => [
                            'name'        => $c->name,
                            'description' => $c->description,
                        ],
                    ],

                    [
                        'type'     => 'delete',
                        'url'      => route(
                            'super.categories.destroy',
                            $c->getRouteKey()
                        ),
                        'label'    => 'Delete',
                        'icon'     => 'trash-2',
                        'confirm'  => "Delete category {$c->name} ?",
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
        CategoryStoreRequest $request,
        CategoryService $service
    ) {
        $category = $service->create(
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Category created',
                'id'      => $category->id,
            ], 201)
            : back()->with(
                'success',
                'Category created'
            );
    }

    public function update(
        CategoryUpdateRequest $request,
        Category $category,
        CategoryService $service
    ) {
        $service->update(
            $category,
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Category updated'
            ])
            : back()->with(
                'success',
                'Category updated'
            );
    }

    public function destroy(
        Category $category,
        CategoryService $service
    ) {
        $service->delete($category);

        return request()->ajax() || request()->expectsJson()
            ? response()->json([
                'message' => 'Category deleted'
            ])
            : redirect()
                ->route('super.categories.index')
                ->with(
                    'success',
                    'Category deleted'
                );
    }
}