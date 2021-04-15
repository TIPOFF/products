<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Products\Exceptions\CartNotAvailableException;
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

    public function view_component()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $this->assertEquals('tipoff-product-cart-item', $product->getViewComponent('cart-item'));
        $this->assertEquals('tipoff-product-order-item', $product->getViewComponent('order-item'));
    }

    /** @test */
    public function cart_item_with_no_service()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $this->expectException(CartNotAvailableException::class);

        $product->createCartItem(2);
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

    /** @test */
    public function scope_by_location()
    {
        Product::factory()->create([
            'location_id' => null,
        ]);

        $this->assertEquals(1, Product::query()->byLocation(1234)->count());
        $this->assertEquals(1, Product::query()->byLocation(null)->count());

        $product = Product::factory()->create([
            'location_id' => Location::factory()->create(),
        ]);

        $this->assertEquals(1, Product::query()->byLocation(1234)->count());
        $this->assertEquals(2, Product::query()->byLocation($product->location_id)->count());
        $this->assertEquals(1, Product::query()->byLocation(null)->count());
    }
}
