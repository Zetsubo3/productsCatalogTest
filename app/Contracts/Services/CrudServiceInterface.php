<?php

namespace App\Contracts\Services;

interface CrudServiceInterface
{
    /**
     * Получить список с фильтрацией и пагинацией
     *
     * @param array $filterParams
     * @return array
     */
    public function index(array $filterParams): array;

    /**
     * Создать новую запись
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array;

    /**
     * Обновить запись
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array;

    /**
     * Удалить запись
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id): array;
}
