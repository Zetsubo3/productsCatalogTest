<?php

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\DTO\PaginatedResponseDTO;
use App\DTO\ProductDTO;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService extends MainService implements ProductServiceInterface
{
    public function __construct(
        protected readonly ProductRepositoryInterface $productRepository,
        protected readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * Возвращает список товаров с пагинацией
     *
     * @param array $filterParams
     * @return PaginatedResponseDTO
     */
    public function index(array $filterParams): PaginatedResponseDTO
    {
        return $this->productRepository->getPaginated($filterParams);
    }

    /**
     * Создать новый товар
     *
     * @param array $data
     * @return ProductDTO
     * @throws NotFoundHttpException
     * @throws ConflictHttpException
     */
    public function store(array $data): ProductDTO
    {
        $category = $this->categoryRepository->findByKey('id', $data['category_id']);

        if (!$category) {
            throw new NotFoundHttpException('Category not found');
        }

        $existingProduct = $this->productRepository->findByKey('name', $data['name']);

        if ($existingProduct) {
            throw new ConflictHttpException('A product with that name already exists');
        }

        return $this->productRepository->create($data);
    }

    /**
     * Обновить товар
     *
     * @param int $id
     * @param array $data
     * @return ProductDTO
     * @throws NotFoundHttpException
     * @throws ConflictHttpException
     */
    public function edit(int $id, array $data): ProductDTO
    {
        $product = $this->productRepository->findByKey('id', $id);

        if (!$product) {
            throw new NotFoundHttpException('The product not found');
        }

        if (isset($data['name'])) {
            $existingProduct = $this->productRepository->findByKey('name', $data['name']);

            if ($existingProduct && $existingProduct->id !== $id) {
                throw new ConflictHttpException('A product with that name already exists');
            }
        }

        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->findByKey('id', $data['category_id']);

            if (!$category) {
                throw new NotFoundHttpException('Category not found');
            }
        }

        return $this->productRepository->update($id, $data);
    }

    /**
     * Удалить товар
     *
     * @param int $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function delete(int $id): true
    {
        $deleted = $this->productRepository->delete($id);

        if (!$deleted) {
            throw new NotFoundHttpException('The product not found');
        }

        return true;
    }
}
