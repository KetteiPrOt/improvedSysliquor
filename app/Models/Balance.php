<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'unitary_price', 'movement_id'];

    public $timestamps = false;

    public function movement(){
        return $this->belongsTo(Movement::class);
    }
}
