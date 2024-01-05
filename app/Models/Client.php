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

    public static $finalConsumerName = 'Consumidor Final';

    public static function finalConsumer(): Client
    {
        return Person::where('name', Client::$finalConsumerName)->first()->client;
    }

    public function person(){
        return $this->belongsTo(Person::class);
    }
}
