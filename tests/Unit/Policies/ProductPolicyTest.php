<?php

declare(strict_types=1);

namespace Tipoff\Products\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Products\Models\Product;
use Tipoff\Products\Tests\TestCase;
use Tipoff\Support\Contracts\Models\UserInterface;

class ProductPolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function view_any()
    {
        $user = self::createPermissionedUser('view products', true);
        $this->assertTrue($user->can('viewAny', Product::class));

        $user = self::createPermissionedUser('view products', false);
        $this->assertFalse($user->can('viewAny', Product::class));
    }

    /**
     * @test
     * @dataProvider data_provider_for_all_permissions_as_creator
     */
    public function all_permissions_as_creator(string $permission, UserInterface $user, bool $expected)
    {
        $product = Product::factory()->make([
            'creator_id' => $user,
        ]);

        $this->assertEquals($expected, $user->can($permission, $product));
    }

    public function data_provider_for_all_permissions_as_creator()
    {
        return [
            'view-true' => [ 'view', self::createPermissionedUser('view products', true), true ],
            'view-false' => [ 'view', self::createPermissionedUser('view products', false), false ],
            'create-true' => [ 'create', self::createPermissionedUser('create products', true), true ],
            'create-false' => [ 'create', self::createPermissionedUser('create products', false), false ],
            'update-true' => [ 'update', self::createPermissionedUser('update products', true), true ],
            'update-false' => [ 'update', self::createPermissionedUser('update products', false), false ],
            'delete-true' => [ 'delete', self::createPermissionedUser('delete products', true), false ],
            'delete-false' => [ 'delete', self::createPermissionedUser('delete products', false), false ],
        ];
    }

    /**
     * @test
     * @dataProvider data_provider_for_all_permissions_not_creator
     */
    public function all_permissions_not_creator(string $permission, UserInterface $user, bool $expected)
    {
        $product = Product::factory()->make();

        $this->assertEquals($expected, $user->can($permission, $product));
    }

    public function data_provider_for_all_permissions_not_creator()
    {
        // Permissions are identical for creator or others
        return $this->data_provider_for_all_permissions_as_creator();
    }
}
