<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'minimun_stock', 'type_id', 'presentation_id'];

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function presentation(){
        return $this->belongsTo(Presentation::class);
    }

    public function salePrices(){
        return $this->hasMany(SalePrice::class);
    }

    public function movements(){
        return $this->hasMany(Movement::class);
    }

    public function productTag(): string
    {
        $tag = $this->type->name . ' ' . 
                $this->name . ' ' . 
                $this->presentation->content . 'ml';
        return $tag;
    }

    public static function searchByTag($search, $pagination = 25){
        $products = Product::join('types', 'products.type_id', '=', 'types.id')
                            ->join('presentations', 'products.presentation_id', '=', 'presentations.id')
                            ->select('products.*')
                            ->whereRaw("
                                CONCAT_WS(' ',
                                    `types`.`name`,
                                    `products`.`name`,
                                    CONCAT(`presentations`.`content`, 'ml')
                                ) LIKE ?
                            ", ["%$search%"])
                            ->paginate($pagination);
        return $products;
    }
}
