<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovementController extends Controller
{
    protected function averageWeighted($amount, $totalPrice): int | float
    {
        if($amount > 0){
            $unitaryPrice = $totalPrice / $amount;
        } else {
            $unitaryPrice = $totalPrice;
        }
        return round($unitaryPrice, 2, PHP_ROUND_HALF_UP);
    }

    public function selectWarehouse(Request $request)
    {
        if($request->has('search')){
            $validator = Validator::make($request->only('search'), [
                'search' => 'nullable|string|max:120',
            ], attributes: [
                'search' => 'Buscar'
            ]);
            if($validator->stopOnFirstFailure()->fails()){
                return redirect()
                            ->route(
                                request()->routeIs('sales.selectWarehouse')
                                ? 'sales.selectWarehouse'
                                : 'purchases.selectWarehouse'
                            )
                            ->withErrors($validator)
                            ->withInput($request->only('search'));
            }
            $validated = $validator->safe()->only('search');
            $search = $validated['search'];
            $warehouses = Warehouse::where('name', 'LIKE', '%' . $search . '%')->paginate(10);
        } else {
            $warehouses = Warehouse::paginate(10);
        }
        return view('kardex.movements.select-warehouse', [
            'warehouses' => $warehouses,
            'search' => $search ?? null
        ]);
    }
    public function saveWarehouse(Request $request)
    {
        $validator = Validator::make($request->only('warehouse'), [
            'warehouse' => 'bail|required|int|exists:warehouses,id',
        ], attributes: [
            'warehouse' => 'Bodega'
        ]);
        if($validator->stopOnFirstFailure()->fails()){
            return redirect()
                        ->route(
                            request()->routeIs('sales.saveWarehouse')
                            ? 'sales.selectWarehouse'
                            : 'purchases.selectWarehouse'
                        )
                        ->withErrors($validator)
                        ->withInput($request->only('warehouse'));
        }
        $validated = $validator->safe()->only('warehouse');
        $warehouse = Warehouse::find($validated['warehouse']);
        $request->session()->put(
            request()->routeIs('sales.saveWarehouse')
                ? 'current-sales-warehouse'
                : 'current-purchases-warehouse',
            $warehouse->id
        );
        return redirect()->route(
            request()->routeIs('sales.saveWarehouse')
                ? 'sales.create'
                : 'purchases.create'
        );
    }
}
