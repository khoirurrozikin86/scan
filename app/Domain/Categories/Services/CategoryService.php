<?php

namespace App\Domain\Categories\Services;

use App\Domain\Categories\Actions\{
    CreateCategoryAction,
    UpdateCategoryAction,
    DeleteCategoryAction
};

use App\Domain\Categories\DTOs\CategoryData;
use App\Models\Category;

class CategoryService
{
    public function __construct(
        protected CreateCategoryAction $create,
        protected UpdateCategoryAction $update,
        protected DeleteCategoryAction $delete,
    ) {}

    public function create(array $payload): Category
    {
        return ($this->create)(
            CategoryData::fromArray($payload)
        );
    }

    public function update(
        Category $category,
        array $payload
    ): Category {
        return ($this->update)(
            $category,
            CategoryData::fromArray($payload)
        );
    }

    public function delete(Category $category): void
    {
        ($this->delete)($category);
    }
}