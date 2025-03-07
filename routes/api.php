<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('{id}', [ProductController::class, 'show']);
    Route::put('{id}', [ProductController::class, 'update']);
    Route::delete('{id}', [ProductController::class, 'destroy']);

    // Endpoints para precios de producto
    Route::get('{id}/prices', [PriceController::class, 'index']);
    Route::post('{id}/prices', [PriceController::class, 'store']);
});
