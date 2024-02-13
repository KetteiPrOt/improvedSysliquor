<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ShowKardexRequest;
use App\Models\Invoice;
use App\Models\MovementCategory;
use App\Models\Movement;
use App\Models\WarehousesExistence;

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
                            ->paginate(25);
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
        if(is_null($request->get('page'))){
            return view('kardex.show', [
                'product' => $product,
                'movements' => $movements,
                'movementCategories' => $movementCategories,
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to']
            ]);
        } else if(is_null($request->get('page')) && !is_null($product)) {
            $movements->lastPage();
            return redirect()->route('kardex.show', [
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'product' => $validated['product'],
                'page' => $movements->lastPage()
            ]);
        } else {
            return redirect()->route('kardex.setQuery');
        }
    }

    public function showMovement(Movement $movement){
        return view('kardex.movements.show', [
            'movement' => $movement,
            'incomeName' => MovementCategory::$incomeName
        ]);
    }

    public function popMovement(Product $product){
        $lastMovement = $product->movements()->orderBy('id', 'desc')->first();
        $movementsCount = $product->movements->count();
        if($lastMovement){
            $invoice = $lastMovement->invoice;
            // update warehouses existence
            $this->updateWarehousesExistence(
                $lastMovement,
                $invoice,
                $movementsCount
            );
            $lastMovement->delete();
            $this->purgeEmptyInvoice($invoice); 
        }
        if($movementsCount == 1){
            $product->started_inventory = false;
            $product->save();
            return redirect()->route('kardex.setQuery');
        } else {
            return redirect()->route('kardex.show', [
                'date_from' => date('Y-m-d', strtotime('-1 month')),
                'date_to' => date('Y-m-d'),
                'product' => $product->id
            ]);
        }
    }

    private function updateWarehousesExistence(
        Movement $lastMovement,
        Invoice $invoice,
        int $movementsCount
    ): void
    {
        $product = $lastMovement->product;
        $warehouse = $lastMovement->warehouse;
        $warehousesExistence = WarehousesExistence::where('product_id', $product->id)
                                    ->where('warehouse_id', $warehouse->id)
                                    ->first();
        $oldAmount = $warehousesExistence?->amount;
        if($movementsCount == 1){
            // delete warehouses existence
            $warehousesExistence->delete();
        } else {
            // update warehouses existence
            if($warehousesExistence){
                if(
                    $invoice->movementCategory->id 
                    == MovementCategory::income()->id
                ){
                    // Restore Warehouses Existence of a bad purchase
                    $warehousesExistence->update([
                        'amount' => $oldAmount - $lastMovement->amount
                    ]);
                } else {
                    // Restore Warehouses Existence of a bad sale
                    $warehousesExistence->update([
                        'amount' => $oldAmount + $lastMovement->amount
                    ]);
                }   
            }
        }        
    }

    private function purgeEmptyInvoice($invoice): void
    {
        if($invoice->movements->count() == 0){
            $invoice->delete();
        }
    }
}
