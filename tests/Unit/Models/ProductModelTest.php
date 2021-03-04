<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Tipoff\Support\Contracts\Checkout\CartInterface;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;

class ProductModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Product::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function cart_item_with_no_service()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $cartItem = $product->createCartItem(2);
        $this->assertNull($cartItem);
    }

    /** @test */
    public function cart_item_with_service()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $cartItem = \Mockery::mock(CartItemInterface::class);
        $cartItem->shouldReceive('setTaxCode')
            ->with($product->tax_code)
            ->once()
            ->andReturnSelf();
        $cartItem->shouldReceive('setLocationId')
            ->with($product->location_id)
            ->once()
            ->andReturnSelf();

        $service = \Mockery::mock(CartInterface::class);
        $service->shouldReceive('createItem')
            ->withArgs(function ($sellable, $itemId, $amount, $quantity) use ($product) {
                return $sellable->id === $product->id &&
                    $itemId === $product->sku &&
                    $amount === $product->amount &&
                    $quantity === 2;
            })
            ->once()
            ->andReturn($cartItem);
        $this->app->instance(CartInterface::class, $service);

        $cartItem = $product->createCartItem(2);
        $this->assertNotNull($cartItem);
    }
}
