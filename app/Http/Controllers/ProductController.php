<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\StoreRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected readonly ProductService $productService
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
            maxAttempts: 60,
            decaySeconds: 60
        );
        if ($rateLimitResponse) {
            return response()->json($rateLimitResponse)->setStatusCode(429);
        }

        $filterParams = $request->getRequestParams();
        $result = $this->productService->show($filterParams);
        return response()->json($result)->setStatusCode($result['http_status']);
    }
}
