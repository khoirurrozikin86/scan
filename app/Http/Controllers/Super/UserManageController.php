<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\{Role, Permission};
use Spatie\Permission\PermissionRegistrar;

class UserManageController extends Controller
{
    public function index(Request $r)
    {
        $q = User::query()->with('roles');
        if ($s = $r->get('s')) $q->where(fn($x) => $x->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        $users = $q->orderBy('name')->paginate(20)->withQueryString();
        return view('super.users.index', compact('users', 's'));
    }

    public function edit(User $user)
    {
        return view('super.users.edit', [
            'user' => $user->load('roles', 'permissions'),
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function syncRoles(Request $r, User $user)
    {
        $roles = Role::whereIn('name', $r->input('roles', []))->get();
        $user->syncRoles($roles);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('ok', 'Roles synced');
    }

    public function syncPermissions(Request $r, User $user)
    {
        $perms = Permission::whereIn('name', $r->input('permissions', []))->get();
        $user->syncPermissions($perms); // direct permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        return back()->with('ok', 'Direct permissions synced');
    }
}
