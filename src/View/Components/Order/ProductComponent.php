<?php

declare(strict_types=1);

namespace Tipoff\Products\View\Components\Order;

use Illuminate\View\Component;
use Illuminate\View\View;
use Tipoff\Products\Models\Product;
use Tipoff\Support\Contracts\Checkout\OrderItemInterface;

class ProductComponent extends Component
{
    public OrderItemInterface $orderItem;
    public Product $sellable;

    public function __construct(OrderItemInterface $orderItem, Product $sellable)
    {
        $this->orderItem = $orderItem;
        $this->sellable = $sellable;
    }

    public function render()
    {
        /** @var View $view */
        $view = view('products::components.order.product');

        return $view;
    }
}
