<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\View\Components;

use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;
use Tipoff\Support\Objects\DiscountableValue;

class ProductComponentTest extends TestCase
{
    /** @test */
    public function single_item()
    {
        /** @var Product $sellable */
        $sellable = Product::factory()->create();
        $cartItem = \Mockery::mock(CartItemInterface::class);
        $cartItem->shouldReceive('getQuantity')->andReturn(1);
        $cartItem->shouldReceive('getAmountEach')->andReturn(new DiscountableValue(1234));
        $cartItem->shouldReceive('getAmountTotal')->andReturn(new DiscountableValue(1234));

        $view = $this->blade(
            '<x-tipoff-product :cart-item="$cartItem" :sellable="$sellable" />',
            [
                'cartItem' => $cartItem,
                'sellable' => $sellable,
            ]
        );

        $view->assertSee($sellable->title);
    }
}
