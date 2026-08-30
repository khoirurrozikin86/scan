<?php

namespace App\Domain\Outlets\Queries;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Builder;

class OutletTableQuery
{
    public function builder(): Builder
    {
        return Outlet::query()
            ->select([
                'id',
                'outlet_code',
                'outlet_name',
                'outlet_type',
                'is_active',
                'created_at',
                'updated_at',
            ])  ->orderByDesc('created_at');
    }
}