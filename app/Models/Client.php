<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['identification_card', 'ruc', 'social_reason', 'person_id'];

    public $timestamps = false;

    public function person(){
        return $this->belongsTo(Person::class);
    }
}
