<?php

declare(strict_types=1);

namespace Tipoff\Products;

use Tipoff\Products\Models\Product;
use Tipoff\Products\Policies\ProductPolicy;
use Tipoff\Products\View\Components;
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
            ->hasNovaResources([
                \Tipoff\Products\Nova\Product::class,
            ])
            ->hasBladeComponents([
                'product-cart-item' => Components\Cart\ProductComponent::class,
                'product-order-item' => Components\Order\ProductComponent::class,
            ])
            ->name('products')
            ->hasViews()
            ->hasConfigFile();
    }
}
