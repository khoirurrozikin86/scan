<?php

namespace App\Domain\Categories\Actions;

use App\Domain\Categories\DTOs\CategoryData;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class UpdateCategoryAction
{
    public function __invoke(
        Category $category,
        CategoryData $data
    ): Category {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data->toArray());

            return $category->refresh();
        });
    }
}