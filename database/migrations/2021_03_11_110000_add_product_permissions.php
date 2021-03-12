<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddProductPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view products' => ['Owner', 'Staff'],
            'create products' => ['Owner'],
            'update products' => ['Owner'],
        ];

        $this->createPermissions($permissions);
    }
}
