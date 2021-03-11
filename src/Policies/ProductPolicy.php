<?php

declare(strict_types=1);

namespace Tipoff\Products\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Products\Models\Product;
use Tipoff\Support\Contracts\Models\UserInterface;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view products') ? true : false;
    }

    public function view(UserInterface $user, Product $product): bool
    {
        return $user->hasPermissionTo('view products') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create products') ? true : false;
    }

    public function update(UserInterface $user, Product $product): bool
    {
        return $user->hasPermissionTo('update products') ? true : false;
    }

    public function delete(UserInterface $user, Product $product): bool
    {
        return false;
    }

    public function restore(UserInterface $user, Product $product): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Product $product): bool
    {
        return false;
    }
}
