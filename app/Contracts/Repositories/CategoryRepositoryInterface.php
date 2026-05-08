<?php

namespace App\Contracts\Repositories;

use App\DTO\CategoryDTO;

interface CategoryRepositoryInterface
{
    /**
     * Найти категорию по колонке и значению
     *
     * @param string $column
     * @param mixed $value
     * @return CategoryDTO|null
     */
    public function findByKey(string $column, mixed $value): ?CategoryDTO;
}
