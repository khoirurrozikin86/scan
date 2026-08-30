<?php

namespace App\Domain\Categories\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DeleteCategoryAction
{
    public function __invoke(Category $category): void
    {
        DB::transaction(
            fn() => $category->delete()
        );
    }
}