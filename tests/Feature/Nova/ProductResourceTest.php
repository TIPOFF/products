<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Feature\Nova;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index()
    {
        Product::factory()->count(4)->create();

        $this->actingAs(self::createPermissionedUser('view products', true));

        $response = $this->getJson('nova-api/products')
            ->assertOk();

        $this->assertCount(4, $response->json('resources'));
    }

    /** @test */
    public function show()
    {
        $product = Product::factory()->create();

        $this->actingAs(self::createPermissionedUser('view products', true));

        $response = $this->getJson("nova-api/products/{$product->id}")
            ->assertOk();

        $this->assertEquals($product->id, $response->json('resource.id.value'));
    }
}
