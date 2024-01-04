<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
use App\Models\Presentation;
use App\Models\SalePrice;

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
}
