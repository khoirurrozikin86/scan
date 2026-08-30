<?php

// app/Domain/Access/Actions/CreateRoleAction.php
namespace App\Domain\Access\Actions;

use App\Domain\Access\DTOs\RoleData;
use Spatie\Permission\Models\Role;

class CreateRoleAction
{
    public function __invoke(RoleData $data): Role
    {
        return Role::create(['name' => $data->name, 'guard_name' => 'web']);
    }
}
