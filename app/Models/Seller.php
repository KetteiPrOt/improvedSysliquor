<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['person_id', 'user_id', 'warehouse_id'];

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function adminSellers(bool $admin){
        // $sellers = Seller::all();
        // foreach($sellers as $key => $seller){
        //     $isAdmin = $seller->user->hasRole('Super Admin');
        //     $condition = $admin ? !$isAdmin : $isAdmin;
        //     if($condition){
        //         $sellers->forget($key);
        //     }
        // }
        // return $sellers;
    }
}
