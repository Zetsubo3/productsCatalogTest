<?php

namespace App\DTO;

class CategoryDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    /**
     * Создать DTO из массива данных
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
        );
    }

    /**
     * Преобразовать DTO в массив для ответа API
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
