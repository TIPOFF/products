<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Support\Providers;

use Tipoff\Products\Nova\Product;
use Tipoff\TestSupport\Providers\BaseNovaPackageServiceProvider;

class NovaPackageServiceProvider extends BaseNovaPackageServiceProvider
{
    public static array $packageResources = [
        Product::class,
    ];
}
