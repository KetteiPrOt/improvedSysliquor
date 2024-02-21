<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesExistence extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'product_id', 'warehouse_id', 'movement_id'];
    
    public $timestamps = false;

    public static function lastMovements($onlyIds = false): array
    {
        $existences = 
            WarehousesExistence::select('product_id', 'movement_id')
                ->orderBy('product_id')
                ->orderBy('movement_id', 'desc')
                ->get()->toArray();
        $movementsIds = [];
        foreach($existences as $existence){
            if(isset($movementsIds[$existence['product_id']])){
                continue;
            } else {
                $movementsIds[$existence['product_id']] = $existence['movement_id'];
            }
        }
        /* $movementsIds = [ $product_id => $last_movement_id ... ] */
        return $onlyIds
                ? $movementsIds
                : Movement::whereIn('id', $movementsIds)->get();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function movement(){
        return $this->belongsTo(Movement::class);
    }
}
