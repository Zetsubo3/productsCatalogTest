<?php

namespace App\Contracts\Repositories;

use App\DTO\PaginatedResponseDTO;
use App\DTO\ProductDTO;

interface ProductRepositoryInterface
{
    /**
     * Получить пагинированный список продуктов с фильтрацией
     *
     * @param array $data Параметры фильтрации и пагинации
     * @return PaginatedResponseDTO
     */
    public function getPaginated(array $data): PaginatedResponseDTO;

    /**
     * Найти продукт по колонке и значению
     *
     * @param string $column
     * @param mixed $value
     * @return ProductDTO|null
     */
    public function findByKey(string $column, mixed $value): ?ProductDTO;

    /**
     * Создать продукт
     *
     * @param array $data
     * @return ProductDTO
     */
    public function create(array $data): ProductDTO;

    /**
     * Обновить продукт
     *
     * @param int $id
     * @param array $data
     * @return ProductDTO|null
     */
    public function update(int $id, array $data): ?ProductDTO;

    /**
     * Удалить продукт
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
