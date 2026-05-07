<?php

namespace App\DTO;

class PaginatedResponseDTO
{
    /**
     * @param array<ProductDTO> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly PaginationDTO $pagination,
    ) {}

    /**
     * Преобразовать DTO в массив для ответа API
     */
    public function toArray(): array
    {
        return [
            'data' => array_map(fn($item) => $item->toArray(), $this->items),
            'pagination' => $this->pagination->toArray(),
        ];
    }
}
