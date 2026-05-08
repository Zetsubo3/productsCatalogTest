<?php

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\CrudServiceInterface;

class ProductService extends MainService implements CrudServiceInterface
{
    public function __construct(
        protected readonly ProductRepositoryInterface $productRepository,
        protected readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * Возвращает список товаров с пагинацией
     *
     * @param array $filterParams
     * @return array
     */
    public function index(array $filterParams): array
    {
        $result = $this->productRepository->getPaginated($filterParams);

        return $this->formatResponse(
            success: true,
            data: $result->toArray(),
            message: 'Products have been successfully received',
            errorCode: null,
            httpStatus: 200
        );
    }

    /**
     * Возвращает ответ о создании товара
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        $category = $this->categoryRepository->findByKey('id', $data['category_id']);

        if (!$category) {
            return $this->formatResponse(
                success: false,
                data: null,
                message: 'Category not found',
                errorCode: 'CATEGORY_NOT_FOUND',
                httpStatus: 404
            );
        }

        $existingProduct = $this->productRepository->findByKey('name', $data['name']);

        if ($existingProduct) {
            return $this->formatResponse(
                success: false,
                data: null,
                message: 'A product with that name already exists',
                errorCode: 'PRODUCT_NAME_ALREADY_EXISTS',
                httpStatus: 409
            );
        }

        $product = $this->productRepository->create($data);

        return $this->formatResponse(
            success: true,
            data: $product->toArray(),
            message: 'The product was successfully created',
            errorCode: null,
            httpStatus: 201
        );
    }

    /**
     * Возвращает ответ об обновлении товара
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function edit(int $id, array $data): array
    {
        $product = $this->productRepository->findByKey('id', $id);

        if (!$product) {
            return $this->formatResponse(
                success: false,
                data: null,
                message: 'The product not found',
                errorCode: 'PRODUCT_NOT_FOUND',
                httpStatus: 404
            );
        }

        // Если обновляется name, проверяем уникальность (исключая текущий товар)
        if (isset($data['name'])) {
            $existingProduct = $this->productRepository->findByKey('name', $data['name']);

            if ($existingProduct && $existingProduct->id !== $id) {
                return $this->formatResponse(
                    success: false,
                    data: null,
                    message: 'A product with that name already exists',
                    errorCode: 'PRODUCT_NAME_ALREADY_EXISTS',
                    httpStatus: 409
                );
            }
        }

        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->findByKey('id', $data['category_id']);

            if (!$category) {
                return $this->formatResponse(
                    success: false,
                    data: null,
                    message: 'Category not found',
                    errorCode: 'CATEGORY_NOT_FOUND',
                    httpStatus: 404
                );
            }
        }

        $updatedProduct = $this->productRepository->update($id, $data);

        return $this->formatResponse(
            success: true,
            data: $updatedProduct->toArray(),
            message: 'The product was successfully updated',
            errorCode: null,
            httpStatus: 200
        );
    }

    /**
     * Возвращает ответ об удаление товара
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        $deleted = $this->productRepository->delete($id);

        if (!$deleted) {
            return $this->formatResponse(
                success: false,
                data: null,
                message: 'The product not found',
                errorCode: 'PRODUCT_NOT_FOUND',
                httpStatus: 404
            );
        }

        return $this->formatResponse(
            success: true,
            data: null,
            message: 'The product was successfully deleted',
            errorCode: null,
            httpStatus: 200
        );
    }
}
