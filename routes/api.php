<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index'])->middleware('rate.limit:index,60,60,ip,method');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->middleware('rate.limit:store,30,60,ip,method');
    Route::put('/products/{id}', [ProductController::class, 'edit'])->middleware('rate.limit:edit,30,60,ip,method');
    Route::delete('/products/{id}', [ProductController::class, 'delete'])->middleware('rate.limit:delete,30,60,ip,method');
});
