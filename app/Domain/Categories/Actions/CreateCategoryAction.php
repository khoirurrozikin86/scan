<?php

namespace App\Domain\Categories\Actions;

use App\Domain\Categories\DTOs\CategoryData;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CreateCategoryAction
{
    public function __invoke(CategoryData $data): Category
    {
        return DB::transaction(
            fn() => Category::create($data->toArray())
        );
    }
}