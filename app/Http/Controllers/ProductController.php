<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CrudServiceInterface;
use App\Http\Requests\Products\EditRequest;
use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\StoreRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected readonly CrudServiceInterface $productService
    ) {}

    /**
     * Ендпоинт в метод каталога товаров
     *
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $filterParams = $request->getRequestParams();
        $paginatedDTO = $this->productService->index($filterParams);

        return response()->json([
            'success' => true,
            'data' => $paginatedDTO->toArray(),
            'message' => 'Products have been successfully received',
            'error_code' => null
        ], 200);
    }

    /**
     * Ендпоинт в метод создания товара
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->getRequestParams();
        $productDTO = $this->productService->store($data);

        return response()->json([
            'success' => true,
            'data' => $productDTO->toArray(),
            'message' => 'The product was successfully created',
            'error_code' => null
        ], 201);
    }

    /**
     * Ендпоинт в метод редактирования товара
     *
     * @param int $id
     * @param EditRequest $request
     * @return JsonResponse
     */
    public function edit(int $id, EditRequest $request): JsonResponse
    {
        $data = $request->getRequestParams();
        $productDTO = $this->productService->edit($id, $data);

        return response()->json([
            'success' => true,
            'data' => $productDTO->toArray(),
            'message' => 'The product was successfully updated',
            'error_code' => null
        ], 200);
    }

    /**
     * Ендпоинт в метод удаления товара
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->productService->delete($id);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'The product was successfully deleted',
            'error_code' => null
        ], 200);
    }
}
