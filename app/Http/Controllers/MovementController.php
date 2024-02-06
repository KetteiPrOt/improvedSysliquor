<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovementController extends Controller
{
    protected function averageWeighted($amount, $totalPrice): int | float
    {
        if($amount > 0){
            $unitaryPrice = $totalPrice / $amount;
        } else {
            $unitaryPrice = $totalPrice / 0.000000000000000000000001;
        }
        return round($unitaryPrice, 2, PHP_ROUND_HALF_UP);
    }
}
