<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;

use App\Domain\Users\DTOs\UserData;
use App\Domain\Users\Actions\{
    CreateUserAction,
    UpdateUserAction,
    DeleteUserAction
};
use App\Domain\Users\Queries\UserTableQuery;
use App\Support\DataTables\Responder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('super.users.index');
    }

    // DataTables server-side (pakai Responder seperti di RoleController)
    public function datatable(UserTableQuery $q)
    {
        return Responder::from($q->builder(), function (User $u) {
            $actions = [
                [
                    'type'       => 'edit',
                    'label'      => 'Edit',
                    'icon'       => 'edit-2',
                    'update_url' => route('super.user.update', $u),
                    'payload'    => [
                        'name'  => $u->name,
                        'email' => $u->email,
                    ],
                ],
                [
                    'type'    => 'delete',
                    'url'     => route('super.user.destroy', $u),
                    'label'   => 'Delete',
                    'icon'    => 'trash-2',
                    'confirm' => "Delete user {$u->name}?",
                    'disabled' => auth()->id() === $u->id, // cegah hapus diri sendiri
                ],
            ];

            // partial yang sama dengan Role: admin/partials/table-actions.blade.php 
            return view('admin.partials.table-actions', compact('actions'))->render();
        });
    }

    public function create()
    {
        $roles = class_exists(Role::class) ? Role::pluck('name', 'name') : collect();
        return view('super.users.form', ['user' => new User, 'roles' => $roles]);
    }

    public function store(UserStoreRequest $req, CreateUserAction $action)
    {
        $action(UserData::fromArray($req->sanitized()));

        if ($req->expectsJson() || $req->ajax()) {
            return response()->json(['message' => 'User created'], 201);
        }
        return redirect()->route('super.users.index')->with('success', 'User created');
    }

    public function edit(User $user)
    {
        $roles = class_exists(Role::class) ? Role::pluck('name', 'name') : collect();
        $userRoles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
        return view('super.users.form', compact('user', 'roles', 'userRoles'));
    }

    public function update(UserUpdateRequest $req, User $user, UpdateUserAction $action)
    {
        $action($user, UserData::fromArray($req->sanitized()));

        if ($req->expectsJson() || $req->ajax()) {
            return response()->json(['message' => 'User updated']);
        }
        return back()->with('success', 'User updated');
    }

    public function destroy(User $user, DeleteUserAction $action)
    {
        abort_if(auth()->id() === $user->id, 403, 'You cannot delete yourself.');
        $action($user);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['message' => 'User deleted']);
        }
        return redirect()->route('super.users.index')->with('success', 'User deleted');
    }
}
