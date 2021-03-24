<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Feature\Nova\ProductModelTest;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;

class ProductModelTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** @test */
    public function create()
    {
        $model = Product::factory()->create();
        $this->assertNotNull($model);
    }
}
