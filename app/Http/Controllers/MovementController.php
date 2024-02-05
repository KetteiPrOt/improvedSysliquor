<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovementController extends Controller
{
    protected function averageWeighted($amount, $totalPrice): int | float
    {
        $unitaryPrice = $totalPrice / $amount;
        return round($unitaryPrice, 2, PHP_ROUND_HALF_UP);
    }
}
