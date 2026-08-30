<?php
// app/Domain/Access/Queries/PermissionTableQuery.php
namespace App\Domain\Access\Queries;

use Spatie\Permission\Models\Permission;

class PermissionTableQuery
{
    public function builder()
    {
        return Permission::query()
            ->select(['id', 'group_name', 'name'])
            ->withCount(['roles']); // kalau mau tampilkan metrik lain, tinggal tambah

    }
}
