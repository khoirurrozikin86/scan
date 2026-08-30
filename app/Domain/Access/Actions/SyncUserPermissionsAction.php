<?php

// app/Domain/Access/Actions/SyncUserPermissionsAction.php
namespace App\Domain\Access\Actions;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class SyncUserPermissionsAction
{
    public function __invoke(User $user, array $permissionNames): void
    {
        $perms = Permission::whereIn('name', $permissionNames)->get();
        $user->syncPermissions($perms);
    }
}
