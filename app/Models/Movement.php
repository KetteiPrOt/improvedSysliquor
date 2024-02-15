<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'unitary_price',
        'amount',
        'total_price',
        'movement_type_id',
        'product_id',
        'invoice_id'
    ];

    public $timestamps = false;

    public function movementType(){
        return $this->belongsTo(MovementType::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function balance(){
        return $this->hasOne(Balance::class);
    }

    public function revenue(){
        return $this->hasOne(Revenue::class);
    }

    public function totalPrice()
    {
        return $this->amount * $this->unitary_price;
    }

    public function isLast(): bool
    {
        $last = $this->product
                     ->movements()
                     ->orderBy('id', 'desc')
                     ->first();
        return $this->id === $last->id;
    }
}
