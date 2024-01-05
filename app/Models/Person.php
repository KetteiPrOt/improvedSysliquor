<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = ['name', 'phone_number', 'email', 'address'];

    public function client(){
        return $this->hasOne(Client::class);
    }
}
