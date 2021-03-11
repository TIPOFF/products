<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddProdcutPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view prodcuts' => ['Owner', 'Staff'],
            'create products' => ['Owner'],
            'update products' => ['Owner'],
        ];

        $this->createPermissions($permissions);
    }
}
