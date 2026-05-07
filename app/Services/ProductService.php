<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Http\Resources\ProductResource;

class ProductService extends MainService
{
    public function __construct(
        protected readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Возвращает список товаров с пагинацией
     *
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        $result = $this->productRepository->getPaginated($data);

        return $this->formatResponse(
            success: true,
            data: $result->toArray(),
            message: 'Products have been successfully received',
            errorCode: null,
            httpStatus: 200
        );
    }

    /**
     * Создаёт новый товар
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        // Проверяем, существует ли товар с таким name
        $existingProduct = $this->productRepository->findByKey('name', $data['name']);

        if ($existingProduct) {
            return $this->formatResponse(
                success: false,
                data: null,
                message: 'Товар с таким названием уже существует',
                errorCode: 'PRODUCT_NAME_ALREADY_EXISTS',
                httpStatus: 409
            );
        }

        $product = $this->productRepository->create($data);

        return $this->formatResponse(
            success: true,
            data: $product->toArray(),
            message: 'Товар успешно создан',
            errorCode: null,
            httpStatus: 201
        );
    }
}
