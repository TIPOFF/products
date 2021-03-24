<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Feature\Nova;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Spatie\Permission\Models\Role;
use Tipoff\Authorization\Models\User;

class ProductResourceTest extends TestCase
{
    use DatabaseTransactions;
    
    private const NOVA_ROUTE = 'nova-api/products';
    
    /** @test */
    public function index()
    {
        Product::factory()->count(3)->create();

        /** @var User $user */
        $user = User::factory()->create()->givePermissionTo(
            Role::findByName('Admin')->getPermissionNames()     // Use individual permissions so we can revoke one
        );
        $this->actingAs($user);

        $response = $this->getJson(self::NOVA_ROUTE)
            ->assertOk();

        $this->assertCount(3, $response->json('resources'));
    }
}
