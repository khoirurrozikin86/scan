<?php

// app/Domain/Access/Actions/DeleteRoleAction.php
namespace App\Domain\Access\Actions;

use Spatie\Permission\Models\Role;

class DeleteRoleAction
{
    public function __invoke(Role $role): void
    {
        if (in_array($role->name, ['super_admin', 'admin', 'user'], true)) {
            throw new \RuntimeException('Role bawaan tidak boleh dihapus.');
        }
        $role->delete();
    }
}
