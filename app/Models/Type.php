<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public $timestamps = false;

    public function products(){
        return $this->hasMany(Product::class);
    }
}
