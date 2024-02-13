<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Movement;
use App\Models\Balance;
use App\Models\MovementCategory;
use App\Models\SalePrice;
use App\Models\Warehouse;
use App\Models\WarehousesExistence;
use Illuminate\Support\Facades\Auth;

class SalesController extends MovementController
{
    public function create(Request $request){
        if(!$request->session()->has('current-sales-warehouse')){
            return redirect()->route('sales.selectWarehouse');
        }
        $lastSale = Auth::user()->lastSale();
        return view('kardex.sales.create', [
            'clients' => Client::all(),
            'finalConsumer' => Client::finalConsumer(),
            'success' => $request->get('success') ?? null,
            'lastSale' => $lastSale,
            'warehouse' => Warehouse::find(
                $request->session()->get('current-sales-warehouse')
            )
        ]);
    }

    public function store(StoreSaleRequest $request){
        $validated = $request->validated();
        $invoiceId = $this->storeInvoice($validated);
        $movementsCount = count($validated['products']);
        for($i = 0; $i <  $movementsCount; $i++){
            // This is the normal sale price
            // $salePrice = SalePrice::find($validated['sale_prices'][$i])->price;
            $data = [
                // 'unitary_price' => $salePrice,
                'amount' => $validated['amounts'][$i],
                'movement_type_id' => $validated['movement_types'][$i],
                'product_id' => $validated['products'][$i],
                'invoice_id' => $invoiceId,
                'warehouse_id' => $validated['warehouse'],
            ];
            $this->registerExpense($data);
        }
        return redirect()->route('sales.create', ['success' => true]);
    }

    private function storeInvoice(array $data): int
    {
        $person = Client::find($data['client'])->person;
        $invoice = Invoice::create([
            'number' => null,
            'date' => date('Y-m-d'),
            'user_id' => auth()->user()->id,
            'person_id' => $person->id,
            'movement_category_id' => MovementCategory::expense()->id
        ]);        
        return $invoice->id;
    }

    private function registerExpense(array $data): void
    {
        // Create Movement
        $lastBalance = Product::find($data['product_id'])
                                ->movements()->orderBy('id', 'desc')->first()->balance;
        $data['unitary_price'] = $lastBalance->unitary_price;
        $totalPrice = round(
            $data['amount'] * $data['unitary_price'],
            2,
            PHP_ROUND_HALF_UP
        );
        $data['total_price'] = $totalPrice;
        $movement = Movement::create($data);
        // Create Balance
        $amount = $lastBalance->amount - $movement->amount;
        $totalPrice = $lastBalance->total_price - $totalPrice;
        $newUnitaryPrice = $this->averageWeighted($amount, $totalPrice);
        Balance::create([
            'amount' => $amount,
            'unitary_price' => $newUnitaryPrice,
            'total_price' => $totalPrice,
            'movement_id' => $movement->id
        ]);
        // Update Warehouses Existence
        $warehousesExistence = WarehousesExistence::where('product_id', $data['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->first();
        $oldAmount = $warehousesExistence->amount;
        $warehousesExistence->update([
            'amount' => $oldAmount - $data['amount']
        ]);
    }

    public function show(Invoice $sale){
        $lastSale = auth()->user()->lastSale();
        if($lastSale){
            if($lastSale->id === $sale->id){
                return view('kardex.sales.show', [
                    'invoice' => $lastSale,
                    'movements' => $lastSale->movements,
                    'movementCategories' => [
                        'income' => MovementCategory::$incomeName,
                        'expense' => MovementCategory::$expenseName
                    ]
                ]);
            }
        }
        return redirect()->route('sales.create');
    }

    public function edit(Movement $movement){
        $validEdit = false;
        $lastSale = Auth::user()->lastSale();
        foreach($lastSale->movements as $validMovement){
            if(
                ($movement->id === $validMovement->id)
                && ($movement->isLast())
            ){
                $validEdit = true;
                break;
            }
        }
        if($validEdit){
            $product = $movement->product;
            $warehouse = $movement->warehouse;
            return view('kardex.sales.edit', [
                'movement' => $movement,
                'movementCategoryId' => MovementCategory::income()->id,
                'warehousesExistence' => $product->warehousesExistences()
                                                ->where('warehouse_id', $warehouse->id)
                                                ->first()
            ]);   
        } else {
            return redirect()->route('sales.create');
        }
    }

    public function update(UpdateSaleRequest $request, Movement $movement){
        $data = $request->validated();
        if($this->validSaleOperation($movement)){
            // This is the normal sale price
            // $data['sale_price']

            // Update Movement
            $oldMovementAmount = $movement->amount;
            $lastBalance = $movement->product
                            ->movements()
                            ->orderBy('id', 'desc')
                            ->get()->get(1)->balance;
            $data['unitary_price'] = $lastBalance->unitary_price;
            $totalPrice = round(
                $data['amount'] * $data['unitary_price'],
                2,
                PHP_ROUND_HALF_UP
            );
            $data['total_price'] = $totalPrice;
            $movement->update([
                'unitary_price' => $data['unitary_price'],
                'amount' => $data['amount'],
                'total_price' => $data['total_price']
            ]);

            // Update Balance
            $amount = $lastBalance->amount - $movement->amount;
            $totalPrice = $lastBalance->total_price - $totalPrice;
            $newUnitaryPrice = $this->averageWeighted($amount, $totalPrice);
            $movement->balance->update([
                'amount' => $amount,
                'unitary_price' => $newUnitaryPrice,
                'total_price' => $totalPrice
            ]);
            // Fix Warehouses Existences
            $product = $movement->product;
            $warehouse = Warehouse::find($data['warehouse']);
            $warehousesExistence = WarehousesExistence::where('product_id', $product->id)
                                    ->where('warehouse_id', $warehouse->id)
                                    ->first();
            $oldAmount = $warehousesExistence?->amount;
            $warehousesExistence->update([
                'amount' => ($oldMovementAmount - $data['amount']) + $oldAmount
            ]);
        }
        return redirect()->route('sales.show', $movement->invoice->id);
    }

    public function destroy(Movement $movement){
        $lastMovement = $movement;
        if($this->validSaleOperation($lastMovement)){
            $product = $lastMovement->product;
            $movementsCount = $product->movements->count();
            $invoice = $lastMovement->invoice;
            // update warehouses existence
            $this->updateWarehousesExistence(
                $lastMovement,
                $invoice,
                $movementsCount
            );
            $lastMovement->delete();
            $this->purgeEmptyInvoice($invoice); 
            if($movementsCount == 1){
                $product->started_inventory = false;
                $product->save();
            }
        }
        return redirect()->route('sales.create');
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

    private function validSaleOperation(Movement $movement): bool
    {
        $valid = false;
        $lastSale = Auth::user()->lastSale();
        foreach($lastSale->movements as $validMovement){
            if(
                ($movement->id === $validMovement->id)
                && ($movement->isLast())
            ){
                $valid = true;
                break;
            }
        }
        return $valid;
    }
}
