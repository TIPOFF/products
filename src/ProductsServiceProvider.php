<?php

declare(strict_types=1);

namespace Tipoff\Products;

use Tipoff\Products\Nova\Product;
use Tipoff\Products\View\Components\ProductCartItemComponent;
use Tipoff\Products\View\Components\ProductOrderItemComponent;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class ProductsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasNovaResources([
                Product::class,
            ])
            ->hasBladeComponents([
                'product-cart-item' => ProductCartItemComponent::class,
                'product-order-item' => ProductOrderItemComponent::class,
            ])
            ->name('products')
            ->hasViews()
            ->hasConfigFile();
    }
}
