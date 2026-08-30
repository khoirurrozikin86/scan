<?php

// app/Http/Controllers/Super/RoleController.php
namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\Access\{RoleStoreRequest, RoleUpdateRequest};
use App\Domain\Access\DTOs\RoleData;
use App\Domain\Access\Actions\{
    CreateRoleAction,
    UpdateRoleAction,
    DeleteRoleAction,
    SyncRolePermissionsAction
};
use App\Domain\Access\Services\PermissionCacheService;
use App\Domain\Access\Queries\RoleTableQuery;
use App\Support\DataTables\Responder;
use Spatie\Permission\Models\{Role, Permission};

class RoleController extends Controller
{
    public function index()
    {
        return view('super.roles.index');
    }

    public function datatable(RoleTableQuery $q)
    {
        return Responder::from($q->builder(), function ($r) {
            $actions = [
                [
                    'type'       => 'edit',   // <= bedakan dari 'link'
                    'label'      => 'Edit',
                    'icon'       => 'edit-2',
                    'update_url' => route('super.roles.update', $r),
                    'name'       => $r->name,
                ],
                [
                    'type'  => 'link',
                    'url'   => route('super.roles.permissions.edit', $r),
                    'label' => 'Permissions',
                    'icon'  => 'shield',
                ],
                [
                    'type'     => 'delete',
                    'url'      => route('super.roles.destroy', $r),
                    'label'    => 'Delete',
                    'icon'     => 'trash-2',
                    'confirm'  => "Delete role {$r->name}?",
                    'disabled' => in_array($r->name, ['super_admin', 'admin', 'user']),
                ],
            ];

            // render Blade reusable component
            return view('admin.partials.table-actions', compact('actions'))->render();
        });
    }


    public function create()
    {
        return view('super.roles.form', ['role' => new Role]);
    }
    public function store(RoleStoreRequest $req, CreateRoleAction $action, PermissionCacheService $cache)
    {
        $action(RoleData::from($req->validated()));
        $cache->reset();

        if ($req->expectsJson() || $req->ajax()) {
            return response()->json(['message' => 'Role created'], 201);
        }

        return redirect()->route('super.roles.index')->with('ok', 'Role created');
    }

    public function edit(Role $role)
    {
        return view('super.roles.form', compact('role'));
    }

    public function update(RoleUpdateRequest $req, Role $role, UpdateRoleAction $action, PermissionCacheService $cache)
    {
        $action($role, RoleData::from($req->validated()));
        $cache->reset();

        if ($req->expectsJson() || $req->ajax()) {
            return response()->json(['message' => 'Role updated']);
        }

        return back()->with('ok', 'Role updated');
    }
    public function destroy(Role $role, DeleteRoleAction $action, PermissionCacheService $cache)
    {
        $action($role);
        $cache->reset();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['message' => 'Role deleted']);
        }

        return redirect()->route('super.roles.index')->with('ok', 'Role deleted');
    }

    public function editPermissions(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $assigned = $role->permissions()->pluck('name')->toArray();
        return view('super.roles.permissions', compact('role', 'permissions', 'assigned'));
    }

    public function updatePermissions(Role $role, SyncRolePermissionsAction $action, PermissionCacheService $cache)
    {
        $action($role, request('permissions', []));
        $cache->reset();
        return back()->with('ok', 'Permissions synced');
    }
}
