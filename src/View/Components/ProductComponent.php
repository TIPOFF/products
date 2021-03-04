<?php

declare(strict_types=1);

namespace Tipoff\Products\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Tipoff\Products\Models\Product;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;

class ProductComponent extends Component
{
    public CartItemInterface $cartItem;
    public Product $sellable;

    public function __construct(CartItemInterface $cartItem, Product $sellable)
    {
        $this->cartItem = $cartItem;
        $this->sellable = $sellable;
    }

    public function render()
    {
        /** @var View $view */
        $view = view('products::components.product');

        return $view;
    }
}
