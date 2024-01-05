<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class Provider extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function person(){
        return $this->belongsTo(Person::class);
    }
}
