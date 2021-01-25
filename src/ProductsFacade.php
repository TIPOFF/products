<?php

namespace Tipoff\Products;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tipoff\Products\Products
 */
class ProductsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'products';
    }
}
