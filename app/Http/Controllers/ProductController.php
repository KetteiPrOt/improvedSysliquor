<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');
        if($search){
            $products = Product::searchByTag($search);
            $formBag['search'] = $search;
        } else {            
            $products = Product::paginate(25);
            $formBag = null;
        }
        return view('products.index', [
            'products' => $products,
            'formBag' => $formBag
        ]);
    }
}
