<?php

namespace App\Repositories;

use App\DTO\PaginatedResponseDTO;
use App\DTO\PaginationDTO;
use App\DTO\ProductDTO;
use App\Filters\ProductFilter;
use App\Models\Product;
use App\Contracts\Repositories\ProductRepositoryInterface;
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        protected readonly ProductFilter $productFilter
    ) {}

    public function getPaginated(array $data): PaginatedResponseDTO
    {
        $query = Product::query()->with('category');
        $query = $this->productFilter->apply($query, $data);

        $perPage = $data['count'];
        $page = $data['page'];

        $paginator = $query->paginate(perPage: $perPage, page: $page);

        $items = array_map(
            fn(Product $product) => $this->mapToDTO($product),
            $paginator->items()
        );

        return new PaginatedResponseDTO(
            items: $items,
            pagination: PaginationDTO::fromPaginator($paginator)
        );
    }

    public function findByKey(string $column, mixed $value): ?ProductDTO
    {
        $product = Product::with('category')->where($column, $value)->first();

        return $product ? $this->mapToDTO($product) : null;
    }

    public function create(array $data): ProductDTO
    {
        $product = Product::query()->create($data);
        $product->load('category');

        return $this->mapToDTO($product);
    }

    public function update(int $id, array $data): ?ProductDTO
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return null;
        }

        $product->update($data);
        $product->load('category');

        return $this->mapToDTO($product);
    }

    public function delete(int $id): bool
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return false;
        }
        return (bool) $product->delete();
    }

    /**
     * Маппинг модели Product в DTO
     *
     * @param Product $product
     * @return ProductDTO
     */
    private function mapToDTO(Product $product): ProductDTO
    {
        return new ProductDTO(
            id: $product->id,
            name: $product->name,
            price: (float) $product->price,
            categoryId: $product->category_id,
            category: $product->relationLoaded('category') && $product->category
                ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ]
                : null,
            createdAt: $product->created_at->toISOString(),
            updatedAt: $product->updated_at->toISOString(),
        );
    }
}
