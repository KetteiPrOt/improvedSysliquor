<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static $incomeName = 'Ingreso';
    public static $expenseName = 'Egreso';

    public static function expense(): MovementCategory
    {
        $expenseName = MovementCategory::$expenseName;
        return MovementCategory::where('name', $expenseName)->first();
    }

    public static function income(): MovementCategory
    {
        $incomeName = MovementCategory::$incomeName;
        return MovementCategory::where('name', $incomeName)->first();
    }

    public function movementTypes(){
        return $this->hasMany(MovementType::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
