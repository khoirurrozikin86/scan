<?php

namespace App\Domain\Users\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserTableQuery
{
    public function builder(): Builder
    {
        // siapkan base query untuk DataTables
        return User::query()
            ->select(['id', 'name', 'email']) // pastikan kolom 'active' ada; kalau tidak, hapus
            ->with('roles:id,name');
    }
}
