<?php

// app/Models/Role.php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /** Default guard untuk semua role baru */
    protected $guard_name = 'web';
}
