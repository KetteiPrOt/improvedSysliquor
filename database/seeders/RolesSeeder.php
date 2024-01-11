<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        Role::create(['name' => 'Super Admin']);
        $seller = Role::create(['name' => 'seller']);

        // Permissions
        $permissions = [
            'products',
            'clients',
            'providers',
            'sellers',
            'purchases',
            'sales',
            'kardex',
            'permissions'
        ];
        foreach($permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $sellerPermissions = [
            'sales'
        ];
        foreach($sellerPermissions as $sellerPermission){
            $seller->givePermissionTo($sellerPermission);
        }
    }
}
