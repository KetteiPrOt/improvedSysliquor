<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static $initialInventoryName = 'Inventario Inicial';
    public static $purchaseName = 'Compra';
    public static $saleName = 'Venta';

    public static function initialInventory(): MovementType
    {
        return MovementType::where('name', MovementType::$initialInventoryName)->first();
    }

    public static function purchase(): MovementType
    {
        return MovementType::where('name', MovementType::$purchaseName)->first();
    }

    public static function sale(): MovementType
    {
        return MovementType::where('name', MovementType::$saleName)->first();
    }

    public function movementCategory(){
        return $this->belongsTo(MovementCategory::class);
    }

    public function movements(){
        return $this->hasMany(Movement::class);
    }
}
