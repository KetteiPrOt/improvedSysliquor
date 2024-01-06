<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static $initialInventoryName = 'Inventario Inicial';

    public function movementCategory(){
        return $this->belongsTo(MovementCategory::class);
    }

    public function movements(){
        return $this->hasMany(Movement::class);
    }
}
