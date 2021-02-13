<?php

declare(strict_types=1);

namespace Tipoff\Products;

use Tipoff\Products\Models\Product;
use Tipoff\Products\Policies\ProductPolicy;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class ProductsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Product::class => ProductPolicy::class,
            ])
            ->name('products')
            ->hasConfigFile();
    }
}
