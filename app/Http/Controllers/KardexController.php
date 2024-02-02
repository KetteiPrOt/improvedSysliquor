<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ShowKardexRequest;
use App\Models\MovementCategory;
use App\Models\Movement;

class KardexController extends Controller
{
    public function setQuery(Request $request){
        $search = $request->get('search');
        if($search){
            $products = Product::searchByTag($search);
            $formBag['search'] = $search;
        }
        return view('kardex.set-query', [
            'products' => $products ?? null,
            'formBag' => $formBag ?? null
        ]);
    }

    public function show(ShowKardexRequest $request){
        $validated = $request->validated();
        $product = Product::find($validated['product']);
        $movements = $product->movements()
                            ->join('invoices', 'movements.invoice_id', '=', 'invoices.id')
                            ->select('movements.*')
                            ->where('invoices.date', '<=', $validated['date_to'])
                            ->where('invoices.date', '>=', $validated['date_from'])
                            ->orderBy('id')
                            ->paginate(5);
        $urlParams = [
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'product' => $validated['product']
        ];
        foreach($urlParams as $key => $param){
            $movements->appends($key, $param);
        }
        $movementCategories = [
            'income' => MovementCategory::$incomeName,
            'expense' => MovementCategory::$expenseName
        ];
        if($request->get('page')){
            return view('kardex.show', [
                'product' => $product,
                'movements' => $movements,
                'movementCategories' => $movementCategories,
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to']
            ]);
        } else {
            $movements->lastPage();
            return redirect()->route('kardex.show', [
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'product' => $validated['product'],
                'page' => $movements->lastPage()
            ]);
        }
    }

    public function showMovement(Movement $movement){
        return view('kardex.movements.show', [
            'movement' => $movement
        ]);
    }
}
