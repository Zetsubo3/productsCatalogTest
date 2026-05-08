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
        $rateLimitResponse = $this->checkRateLimit(
            methodName: 'index',
            keys: [request()->ip()],
            maxAttempts: 60,
            decaySeconds: 60
        );
        if ($rateLimitResponse) {
            return response()->json($rateLimitResponse)->setStatusCode(429);
        }

        $filterParams = $request->getRequestParams();
        $result = $this->productService->index($filterParams);

        return response()->json($result)->setStatusCode($result['http_status']);
    }

    /**
     * Ендпоинт в метод создания товара
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $rateLimitResponse = $this->checkRateLimit(
            methodName: 'store',
            keys: [request()->ip()],
            maxAttempts: 20,
            decaySeconds: 60
        );
        if ($rateLimitResponse) {
            return response()->json($rateLimitResponse)->setStatusCode(429);
        }

        $filterParams = $request->getRequestParams();
        $result = $this->productService->store($filterParams);
        return response()->json($result)->setStatusCode($result['http_status']);
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
        $rateLimitResponse = $this->checkRateLimit(
            methodName: 'edit',
            keys: [request()->ip()],
            maxAttempts: 20,
            decaySeconds: 60
        );
        if ($rateLimitResponse) {
            return response()->json($rateLimitResponse)->setStatusCode(429);
        }

        $filterParams = $request->getRequestParams();
        $result = $this->productService->edit($id ,$filterParams);
        return response()->json($result)->setStatusCode($result['http_status']);
    }

    /**
     * Ендпоинт в метод удаления товара
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $rateLimitResponse = $this->checkRateLimit(
            methodName: 'delete',
            keys: [request()->ip()],
            maxAttempts: 20,
            decaySeconds: 60
        );
        if ($rateLimitResponse) {
            return response()->json($rateLimitResponse)->setStatusCode(429);
        }

        $result = $this->productService->delete($id);
        return response()->json($result)->setStatusCode($result['http_status']);
    }
}
