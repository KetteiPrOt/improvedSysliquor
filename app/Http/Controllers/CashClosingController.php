<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowCashClosingRequest;
use Illuminate\Http\Request;

class CashClosingController extends Controller
{
    public function query()
    {
        return view('cash-closing.query');
    }

    public function show(ShowCashClosingRequest $request)
    {
        dump($request->validated());
    }
}
