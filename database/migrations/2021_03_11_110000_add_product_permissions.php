<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddProductPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view products' => ['Owner', 'Executive', 'Staff'],
            'create products' => ['Owner', 'Executive'],
            'update products' => ['Owner', 'Executive'],
        ];

        $this->createPermissions($permissions);
    }
}
