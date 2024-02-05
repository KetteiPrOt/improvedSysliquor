<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'date', 'user_id', 'person_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function movements(){
        return $this->hasMany(Movement::class);
    }

    public static function constructInvoiceNumber(array $parts): string
    {
        $number = '';
        foreach($parts as $key => $part){
            if($key == 2){
                for($i = 1; $i < 10; $i++){
                    if($part < (10 ** $i)){
                        for($j = 0; $j < (9 - $i); $j++){
                            $number .= '0';
                        }
                        $number .= $part;
                        break;
                    }
                }
                // if($part < 10){
                //     $number .= '00000000'.$part;
                // } else if($part < 100){
                //     $number .= '0000000'.$part;
                // } else if($part < 1000){
                //     $number .= '000000'.$part;
                // } else if($part < 10000){
                //     $number .= '000000'.$part;
                // } else if($part < 100000){
                //     $number .= '00000'.$part;
                // } else if($part < 1000000){
                //     $number .= '0000'.$part;
                // } else if($part < 10000000){
                //     $number .= '000'.$part;
                // } else if($part < 100000000){
                //     $number .= '00'.$part;
                // } else if($part < 1000000000){
                //     $number .= '0'.$part;
                // } else {
                //     $number .= $part;
                // }
            } else {
                if($part < 10){
                    $number .= '00'.$part;
                } else if($part < 100){
                    $number .= '0'.$part;
                } else {
                    $number .= $part;
                }
            }
        }
        return $number;
    }

    public function showInvoiceNumber(): string
    {
        $invoiceNumber = $this->number;
        $result = substr($invoiceNumber, 0, 3)
                  . '-'
                  . substr($invoiceNumber, 3, 3)
                  . '-'
                  . substr($invoiceNumber, 6);
        return $result;
    }
}
