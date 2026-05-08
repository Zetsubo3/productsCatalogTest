<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\DTO\CategoryDTO;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function findByKey(string $column, mixed $value): ?CategoryDTO
    {
        $category = Category::query()->where($column, $value)->first();

        return $category ? $this->mapToDTO($category) : null;
    }

    /**
     * Маппинг модели Category в DTO
     *
     * @param Category $category
     * @return CategoryDTO
     */
    private function mapToDTO(Category $category): CategoryDTO
    {
        return new CategoryDTO(
            id: $category->id,
            name: $category->name,
            createdAt: $category->created_at->toISOString(),
            updatedAt: $category->updated_at->toISOString(),
        );
    }
}
