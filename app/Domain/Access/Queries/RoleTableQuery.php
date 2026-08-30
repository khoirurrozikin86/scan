<?php

// app/Domain/Access/Queries/RoleTableQuery.php
namespace App\Domain\Access\Queries;

use Spatie\Permission\Models\Role;

class RoleTableQuery
{
    public function builder()
    {
        return Role::query()->withCount(['permissions', 'users'])->orderBy('name');
    }
}
