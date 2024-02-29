<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    public static array $permissions = [
        'products',
        'clients',
        'providers',
        'sellers',
        'purchases',
        'sales',
        'kardex',
        'cash-closing',
        'inventory',
        'permissions'
    ];

    public static array $directPermissions = [
        'products',
        'clients',
        'providers',
        'sellers',
        'purchases',
        'sales',
        'kardex',
        'cash-closing',
        'inventory',
        // Only role Administrator can manage permissions
        // 'permissions'
    ];

    public static array $permissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        'sellers' => 'Vendedores',
        'purchases' => 'Compras',
        'sales' => 'Ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de Caja',
        'inventory' => 'Reporte de stock',
        'permissions' => 'Parametrizar permisos'
    ];

    public static array $directPermissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        'sellers' => 'Vendedores',
        'purchases' => 'Compras',
        'sales' => 'Ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de Caja',
        'inventory' => 'Reporte de stock',
        // Only role Administrator can manage permissions
        // 'permissions'
    ];
}
