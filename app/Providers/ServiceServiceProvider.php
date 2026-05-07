<?php

namespace App\Providers;

use App\Http\Controllers\ProductController;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\CrudServiceInterface;
use App\Services\ProductService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(ProductController::class)
            ->needs(CrudServiceInterface::class)
            ->give(ProductService::class);
    }
}
