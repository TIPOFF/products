<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tipoff\Authorization\Http\Middleware\TipoffAuthenticate;
use Tipoff\Products\Http\Controllers\ProductsController;

Route::middleware(config('tipoff.web.middleware_group'))
    ->prefix(config('tipoff.web.uri_prefix'))
    ->group(function () {
        Route::getLocation('products', 'products', [ProductsController::class, 'index']);

        // PROTECTED ROUTES
        Route::middleware(TipoffAuthenticate::class.':email,web')->group(function () {
            Route::post('products/add-to-cart', [ProductsController::class, 'addToCart'])->name('products.add-to-cart');
        });
    });
