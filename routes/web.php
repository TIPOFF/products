<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tipoff\Authorization\Http\Middleware\TipoffAuthenticate;
use Tipoff\Authorization\Http\Middleware\VerifyCsrfTokenGet;
use Tipoff\Products\Http\Controllers\ProductsController;

Route::middleware(config('tipoff.web.middleware_group'))
    ->prefix(config('tipoff.web.uri_prefix'))
    ->group(function () {
        Route::getLocation('products', 'products', [ProductsController::class, 'index']);

        // PROTECTED ROUTES
        Route::middleware([
            TipoffAuthenticate::class.':email,web',
            VerifyCsrfTokenGet::class,
        ])->group(function () {
            Route::get('products/{product}/add-to-cart/{quantity}/{_token?}', [ProductsController::class, 'addToCart'])
                ->where('quantity', '[1-9][0-9]*')
                ->name('products.add-to-cart');
        });
    });
