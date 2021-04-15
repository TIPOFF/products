<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\EmailAddress;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationResolver;
use Tipoff\Products\Exceptions\CartNotAvailableException;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Tipoff\Support\Contracts\Checkout\CartInterface;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;

class ProductsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function single_market_single_location()
    {
        $this->logToStderr();

        $this->actingAs(User::factory()->create());

        $location = Location::factory()->create();
        $market = $location->market;

        $this->get($this->webUrl("company/products"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} L:{$location->id} P:0 --");

        $this->get($this->webUrl("{$market->slug}/products"))
            ->assertRedirect('company/products');

        $this->get($this->webUrl("{$market->slug}/{$location->slug}/products"))
            ->assertRedirect('company/products');
    }

    /** @test */
    public function multiple_markets_single_locations()
    {
        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get($this->webUrl("company/products"))
            ->assertOk()
            ->assertSee("-- SELECT:0 --");

        $this->get($this->webUrl("{$market->slug}/products"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} L:{$location->id} P:0 --");

        $this->get($this->webUrl("{$market->slug}/{$location->slug}/products"))
            ->assertRedirect("{$market->slug}/products");
    }

    /** @test */
    public function multiple_markets_multiple_locations()
    {
        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->count(2)->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get($this->webUrl("company/products"))
            ->assertOk()
            ->assertSee("-- SELECT:0 --");

        $this->get($this->webUrl("{$market->slug}/products"))
            ->assertOk()
            ->assertSee("-- SELECT:{$market->id} --");

        $this->get($this->webUrl("{$market->slug}/{$location->slug}/products"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} L:{$location->id} P:0 --");
    }

    /** @test */
    public function add_to_cart_no_service()
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create();
        $this->post($this->webUrl("products/add-to-cart"), [
                'id' => $product->id,
                'quantity' => 1,
            ])->assertStatus(500);
    }

    /** @test */
    public function add_to_cart_with_service()
    {
        $this->logToStderr();

        $cartItem = \Mockery::mock(CartItemInterface::class);
        $cartItem->shouldReceive('setLocationId')->once()->andReturnSelf();
        $cartItem->shouldReceive('setTaxCode')->once()->andReturnSelf();

        $service = \Mockery::mock(CartInterface::class);
        $service->shouldReceive('activeCart')->once()->andReturnSelf();
        $service->shouldReceive('createItem')->once()->andReturn($cartItem);
        $service->shouldReceive('upsertItem')->once()->andReturn($cartItem);

        $this->app->instance(CartInterface::class, $service);

        $this->actingAs(EmailAddress::factory()->create(), 'email');

        $product = Product::factory()->create([
            'location_id' => Location::factory()->create(),
        ]);
        session([LocationResolver::TIPOFF_LOCATION => $product->location->id]);

        $this->post($this->webUrl("products/add-to-cart"), [
            'id' => $product->id,
            'quantity' => 1,
        ])->assertRedirect();
    }
}
