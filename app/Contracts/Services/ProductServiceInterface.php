<?php

namespace App\Contracts\Services;

use App\DTO\PaginatedResponseDTO;
use App\DTO\ProductDTO;

interface ProductServiceInterface extends CrudServiceInterface
{
    /**
     * Получить список с фильтрацией и пагинацией
     *
     * @param array $filterParams
     * @return PaginatedResponseDTO
     */
    public function index(array $filterParams): PaginatedResponseDTO;

    /**
     * Создать новую запись
     *
     * @param array $data
     * @return ProductDTO
     */
    public function store(array $data): ProductDTO;

    /**
     * Обновить запись
     *
     * @param int $id
     * @param array $data
     * @return ProductDTO
     */
    public function edit(int $id, array $data): ProductDTO;

    /**
     * Удалить запись
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
