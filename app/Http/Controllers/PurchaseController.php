<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\MovementCategory;
use App\Models\MovementType;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Invoice;
use App\Models\Movement;
use App\Models\Balance;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehousesExistence;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends MovementController
{
    public function create(Request $request){
        if(!$request->session()->has('current-purchases-warehouse')){
            return redirect()->route('purchases.selectWarehouse');
        }
        $incomeCategory = MovementCategory::income();
        return view('kardex.purchases.create', [
            'providers' => Provider::all(),
            'movementTypes' => $incomeCategory->movementTypes,
            'purchaseType' => MovementType::purchase(),
            'success' => $request->get('success') ?? null,
            'warehouse' => Warehouse::find(
                $request->session()->get('current-purchases-warehouse')
            )
        ]);
    }

    public function store(StorePurchaseRequest $request){
        $validated = $request->validated();
        $invoices = Invoice::all();
        $number = $this->getInvoiceNumber($validated);
        foreach($invoices as $invoice){
            if(
                ($invoice->number === $number)
                && ($number !== null)
            ){
                $invoiceId = $invoice->id;
                break;
            }
        }
        if(!isset($invoiceId)){
            $invoiceId = $this->storeInvoice($validated, $number);
        }
        $movementsCount = count($validated['products']);
        for($i = 0; $i <  $movementsCount; $i++){
            $data = [
                'unitary_price' => $validated['unitary_prices'][$i],
                'amount' => $validated['amounts'][$i],
                'movement_type_id' => $validated['movement_types'][$i],
                'product_id' => $validated['products'][$i],
                'invoice_id' => $invoiceId,
                'warehouse_id' => $validated['warehouse'],
            ];
            $this->registerMovement($data);
        }
        return redirect()->route('purchases.create', ['success' => true]);
    }

    private function storeInvoice(array $data, string|null $number): int
    {
        if(isset($data['provider'])){
            $personId = Provider::find($data['provider'])->person->id;
        } else {
            $personId = null;
        }
        $invoice = Invoice::create([
            'number' => $number,
            'date' => $data['date'],
            'user_id' => Auth::user()->id,
            'person_id' => $personId,
            'movement_category_id' => MovementCategory::income()->id
        ]);        
        return $invoice->id;
    }

    private function getInvoiceNumber(array $data): string|null
    {
        if(
            is_null($data['invoice_number'][0])
            || is_null($data['invoice_number'][1])
            || is_null($data['invoice_number'][2])
        ){
            $number = null;
        } else {
            $number = Invoice::constructInvoiceNumber($data['invoice_number']);
        }
        return $number;
    }

    private function registerMovement(array $data): void
    {
        if($data['movement_type_id'] == MovementType::initialInventory()->id){
            $this->startInventory($data);
        } else {
            $this->pushIncome($data);
        }
    }

    private function startInventory(array $data): void
    {
        // Create Movement
        $totalPrice = round(
            $data['amount'] * $data['unitary_price'],
            2,
            PHP_ROUND_HALF_UP
        );
        $data['total_price'] = $totalPrice;
        $movement = Movement::create($data);
        // Create Balance
        Balance::create([
            'amount' => $data['amount'],
            'unitary_price' => $data['unitary_price'],
            'total_price' => $totalPrice,
            'movement_id' => $movement->id
        ]);
        $product = Product::find($data['product_id']);
        $product->started_inventory = true;
        $product->save();
        // Create Warehouses Existence
        WarehousesExistence::create([
            'amount' => $data['amount'],
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['warehouse_id']
        ]);
    }

    private function pushIncome(array $data): void
    {
        // Create Movement
        $product = Product::find($data['product_id']);
        $lastBalance = $product->movements()->orderBy('id', 'desc')->first()->balance;
        $totalPrice = round(
            $data['amount'] * $data['unitary_price'],
            2,
            PHP_ROUND_HALF_UP
        );
        $data['total_price'] = $totalPrice;
        $movement = Movement::create($data);
        // Create Balance
        $amount = $lastBalance->amount + $movement->amount;
        $totalPrice = $lastBalance->totalPrice() + $movement->totalPrice();
        $newUnitaryPrice = $this->averageWeighted($amount, $totalPrice);
        Balance::create([
            'amount' => $amount,
            'unitary_price' => $newUnitaryPrice,
            'total_price' => $totalPrice,
            'movement_id' => $movement->id,
        ]);
        // Warehouses Existence
        $warehousesExistence = WarehousesExistence::where('product_id', $data['product_id'])
                ->where('warehouse_id', $data['warehouse_id'])
                ->first();
        if($warehousesExistence){
            // Update Warehouses Existence
            $oldAmount = $warehousesExistence->amount;
            $warehousesExistence->update([
                'amount' => $oldAmount + $data['amount']
            ]);
        } else {
            // Create Warehouses Existence
            WarehousesExistence::create([
                'amount' => $data['amount'],
                'product_id' => $data['product_id'],
                'warehouse_id' => $data['warehouse_id']
            ]);
        }
    }
}
