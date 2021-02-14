<?php

declare(strict_types=1);

namespace Tipoff\Products;

use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class ProductsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->name('products')
            ->hasConfigFile();
    }
}
