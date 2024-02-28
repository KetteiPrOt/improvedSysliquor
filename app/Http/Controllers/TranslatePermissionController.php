<?php

namespace App\Http\Controllers;

use App\Models\Permission;

class TranslatePermissionController
{
    public array $directPermissions;
    
    public array $permissions;

    private array $permissionNames;

    public function __construct()
    {
        $this->directPermissions = Permission::$directPermissions;
        $this->permissionNames = Permission::$permissionNames;
        $this->permissions = Permission::$permissions;
    }

    public function translate(string $permission): string
    {
        return $this->permissionNames[$permission];
    }
}
