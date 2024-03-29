<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function seller(){
        return $this->hasOne(Seller::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function lastSale(): Invoice|null
    {
        $user = Auth::user();
        $lastSaleInvoice = $user->invoices()
                            ->where(
                                'movement_category_id',
                                MovementCategory::expense()->id
                            )
                            ->orderBy('id', 'desc')
                            ->first();
        return $lastSaleInvoice;
    }
}
