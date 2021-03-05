<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\View\Components\Order;

use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;
use Tipoff\Support\Contracts\Checkout\OrderItemInterface;
use Tipoff\Support\Objects\DiscountableValue;

class ProductOrderItemComponentTest extends TestCase
{
    /** @test */
    public function single_item()
    {
        /** @var Product $sellable */
        $sellable = Product::factory()->create();
        $orderItem = \Mockery::mock(OrderItemInterface::class);
        $orderItem->shouldReceive('getQuantity')->andReturn(1);
        $orderItem->shouldReceive('getAmountEach')->andReturn(new DiscountableValue(1234));
        $orderItem->shouldReceive('getAmountTotal')->andReturn(new DiscountableValue(1234));

        $view = $this->blade(
            '<x-tipoff-product-order-item :order-item="$orderItem" :sellable="$sellable" />',
            [
                'orderItem' => $orderItem,
                'sellable' => $sellable,
            ]
        );

        $view->assertSee($sellable->title);
    }
}
