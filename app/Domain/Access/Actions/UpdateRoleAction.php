<?php

// app/Domain/Access/Actions/UpdateRoleAction.php
namespace App\Domain\Access\Actions;

use App\Domain\Access\DTOs\RoleData;
use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    public function __invoke(Role $role, RoleData $data): Role
    {
        if ($role->name === 'super_admin' && $data->name !== 'super_admin') {
            throw new \RuntimeException('Role super_admin tidak boleh diganti.');
        }
        $role->update(['name' => $data->name]);
        return $role;
    }
}
