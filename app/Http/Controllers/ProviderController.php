<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;

class ProviderController extends Controller
{
    public function index(){
        $providers = Provider::paginate(25);
        return view('entities.providers.index', ['providers' => $providers]);
    }
}
