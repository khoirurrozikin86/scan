<?php

// app/Domain/Access/Actions/SyncRolePermissionsAction.php
namespace App\Domain\Access\Actions;

use Spatie\Permission\Models\{Role, Permission};

class SyncRolePermissionsAction
{
    public function __invoke(Role $role, array $permissionNames): void
    {
        $perms = Permission::whereIn('name', $permissionNames)->get();
        $role->syncPermissions($perms);
    }
}
