<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class SalePrice extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'units_number_id', 'product_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
