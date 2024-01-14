<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class Provider extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['ruc', 'social_reason', 'person_id'];

    public function person(){
        return $this->belongsTo(Person::class);
    }
}
