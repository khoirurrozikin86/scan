<?php

// app/Models/Permission.php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /** Default guard untuk semua permission baru */
    protected $guard_name = 'web';
}
