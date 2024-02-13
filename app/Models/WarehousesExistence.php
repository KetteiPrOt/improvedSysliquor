<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesExistence extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'product_id', 'warehouse_id'];
    
    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
}
