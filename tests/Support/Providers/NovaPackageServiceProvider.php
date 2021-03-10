<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Support\Providers;

use Tipoff\TestSupport\Providers\BaseNovaPackageServiceProvider;
use Tipoff\Products\Nova\Product;

class NovaPackageServiceProvider extends BaseNovaPackageServiceProvider
{
	Product::class,
}
