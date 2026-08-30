<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Support\DataTables\Responder;
use App\Domain\Access\Queries\PermissionTableQuery;

class PermissionController extends Controller
{
    public function index()
    {
        // Sama seperti Roles: view kosong, DataTables yang isi tbody
        return view('super.permissions.index');
    }

    public function datatable(PermissionTableQuery $q)
    {
        return Responder::from($q->builder(), function ($r) {
            // Samakan struktur actions dengan Role agar partial bisa dipakai ulang
            $actions = [
                [
                    'type'       => 'edit',
                    'label'      => 'Edit',
                    'icon'       => 'edit-2',
                    'update_url' => route('super.permissions.update', $r),
                    'name'       => $r->name,
                    'group_name' => $r->group_name,
                ],
                [
                    'type'     => 'delete',
                    'url'      => route('super.permissions.destroy', $r),
                    'label'    => 'Delete',
                    'icon'     => 'trash-2',
                    'confirm'  => "Delete permission {$r->name}?",
                    // Optional: lindungi permission inti agar tidak terhapus
                    'disabled' => in_array($r->name, ['roles.view', 'roles.create', 'roles.update', 'roles.delete']),
                ],
            ];

            return view('admin.partials.table-actions', compact('actions'))->render();
        });
    }

    public function create()
    {
        return view('super.permissions.form', ['permission' => new Permission]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9_.-]+$/|unique:permissions,name',
            'group_name' => 'nullable|string|max:100',
        ]);

        $p = Permission::create([
            'name'       => $data['name'],
            'group_name' => $data['group_name'] ?? null,
            'guard_name' => 'web',
        ]);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        if ($r->expectsJson() || $r->ajax()) {
            return response()->json(['message' => 'Permission created', 'data' => ['id' => $p->id, 'name' => $p->name]], 201);
        }

        return redirect()->route('super.permissions.index')->with('ok', 'Permission created');
    }

    public function edit(Permission $permission)
    {
        return view('super.permissions.form', compact('permission'));
    }

    public function update(Request $r, Permission $permission)
    {
        $data = $r->validate([
            'name'       => "required|string|regex:/^[a-zA-Z0-9_.-]+$/|unique:permissions,name,{$permission->id}",
            'group_name' => 'nullable|string|max:100',
        ]);

        $permission->update($data);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        if ($r->expectsJson() || $r->ajax()) {
            return response()->json(['message' => 'Permission updated']);
        }

        return back()->with('ok', 'Permission updated');
    }

    public function destroy(Request $r, Permission $permission)
    {
        $name = $permission->name;
        // Optional: cegah hapus permission inti
        if (in_array($name, ['roles.view', 'roles.create', 'roles.update', 'roles.delete'])) {
            $msg = 'This permission is protected and cannot be deleted.';
            return $r->expectsJson() || $r->ajax()
                ? response()->json(['message' => $msg], 422)
                : back()->with('ok', $msg);
        }

        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        if ($r->expectsJson() || $r->ajax()) {
            return response()->json(['message' => 'Permission deleted']);
        }

        return redirect()->route('super.permissions.index')->with('ok', 'Permission deleted');
    }
}
