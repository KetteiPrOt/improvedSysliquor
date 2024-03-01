<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'active'];
    
    public $timestamps = false;

    public function products(){
        return $this->hasMany(Product::class);
    }
}
