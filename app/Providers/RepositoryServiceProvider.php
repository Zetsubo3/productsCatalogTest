<?php

namespace App\Providers;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Repositories\CachedProductRepository;
use App\Repositories\CategoryRepository;
use App\Services\CacheKeyService;
use App\Services\CacheService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // продукты - с кэшем
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {
            return new CachedProductRepository(
                decorated: $app->make(ProductRepository::class),
                cacheService: $app->make(CacheService::class),
                cacheKeyService: $app->make(CacheKeyService::class)
            );
        });

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }
}
