<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\PaginatedResponseDTO;
use App\DTO\ProductDTO;
use App\Services\CacheService;
use App\Services\CacheKeyService;

class CachedProductRepository implements ProductRepositoryInterface
{
    private const CACHE_TTL = 300;

    public function __construct(
        private readonly ProductRepositoryInterface $decorated,
        private readonly CacheService $cacheService,
        private readonly CacheKeyService $cacheKeyService
    ) {}

    public function getPaginated(array $data): PaginatedResponseDTO
    {
        $key = $this->cacheKeyService->productsIndexKey($data);

        return $this->cacheService->remember($key, self::CACHE_TTL, function () use ($data) {
            return $this->decorated->getPaginated($data);
        });
    }

    public function findByKey(string $column, mixed $value): ?ProductDTO
    {
        return $this->decorated->findByKey($column, $value);
    }

    public function create(array $data): ProductDTO
    {
        $result = $this->decorated->create($data);
        $this->cacheService->forget();
        return $result;
    }

    public function update(int $id, array $data): ?ProductDTO
    {
        $result = $this->decorated->update($id, $data);
        if ($result) {
            $this->cacheService->forget();
        }
        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->decorated->delete($id);
        if ($result) {
            $this->cacheService->forget();
        }
        return $result;
    }
}
