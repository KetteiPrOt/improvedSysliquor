<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['unitary_price', 'amount', 'movement_type_id', 'product_id', 'invoice_id'];

    public $timestamps = false;

    public function movementType(){
        return $this->belongsTo(MovementType::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }

    public function balance(){
        return $this->hasOne(Balance::class);
    }
}
