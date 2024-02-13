<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    public $timestamps = false;

    public static $depositName = 'Depósito';
    public static $liquorStoreName = 'Licoreria';

    public static function deposit(): Warehouse
    {
        return Warehouse::where('name', Warehouse::$depositName)->first();
    }

    public static function liquorStore(): Warehouse
    {
        return Warehouse::where('name', Warehouse::$liquorStoreName)->first();
    }

    public function sellers(){
        return $this->hasMany(Seller::class);
    }

    public function movements(){
        return $this->hasMany(Movement::class);
    }

    public function warehousesExistences(){
        return $this->hasMany(WarehousesExistence::class);
    }
}
