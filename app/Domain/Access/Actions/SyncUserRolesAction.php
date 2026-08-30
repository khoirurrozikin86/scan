<?php

// app/Domain/Access/Actions/SyncUserRolesAction.php
namespace App\Domain\Access\Actions;

use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncUserRolesAction
{
    public function __invoke(User $user, array $roleNames): void
    {
        $roles = Role::whereIn('name', $roleNames)->get();
        $user->syncRoles($roles);
    }
}
