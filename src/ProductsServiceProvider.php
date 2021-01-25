<?php

namespace Tipoff\Products;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Products\Commands\ProductsCommand;

class ProductsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('products')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_products_table')
            ->hasCommand(ProductsCommand::class);
    }
}
