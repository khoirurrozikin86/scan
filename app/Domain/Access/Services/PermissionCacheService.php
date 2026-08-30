<?php

// app/Domain/Access/Services/PermissionCacheService.php
namespace App\Domain\Access\Services;

use Spatie\Permission\PermissionRegistrar;

class PermissionCacheService
{
    public function reset(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
