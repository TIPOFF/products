<?php

declare(strict_types=1);

namespace Tipoff\Products;

use Tipoff\Products\View\Components\ProductComponent;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class ProductsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasBladeComponents([
                'product' => ProductComponent::class,
            ])
            ->name('products')
            ->hasViews()
            ->hasConfigFile();
    }
}
