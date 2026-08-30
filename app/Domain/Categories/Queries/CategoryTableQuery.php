<?php

namespace App\Domain\Categories\Queries;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryTableQuery
{
    public function builder(): Builder
    {
        return Category::query()
            ->select([
                'id',
                'name',
                'description',
                'updated_at',
                'created_at',
            ]);
    }
}