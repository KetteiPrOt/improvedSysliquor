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

class PurchaseController extends MovementController
{
    public function create(Request $request){
        $incomeCategory = MovementCategory::income();
        return view('kardex.purchases.create', [
            'providers' => Provider::all(),
            'movementTypes' => $incomeCategory->movementTypes,
            'purchaseType' => MovementType::purchase(),
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StorePurchaseRequest $request){
        $validated = $request->validated();
        $invoiceId = $this->storeInvoice($validated);
        $movementsCount = count($validated['products']);
        for($i = 0; $i <  $movementsCount; $i++){
            $data = [
                'unitary_price' => $validated['unitary_prices'][$i],
                'amount' => $validated['amounts'][$i],
                'movement_type_id' => $validated['movement_types'][$i],
                'product_id' => $validated['products'][$i],
                'invoice_id' => $invoiceId
            ];
            $this->registerMovement($data);
        }
        return redirect()->route('purchases.create', ['success' => true]);
    }

    private function storeInvoice(array $data): int
    {
        if(isset($data['invoice_number'])){
            $number = Invoice::constructInvoiceNumber($data['invoice_number']);
        } else {
            $number = null;
        }
        if(isset($data['provider'])){
            $personId = Provider::find($data['provider'])->person->id;
        } else {
            $personId = null;
        }
        $invoice = Invoice::create([
            'number' => $number,
            'date' => $data['date'],
            'user_id' => auth()->user()->id,
            'person_id' => $personId
        ]);        
        return $invoice->id;
    }

    private function registerMovement(array $data){
        if($data['movement_type_id'] == MovementType::initialInventory()->id){
            $this->startInventory($data);
        } else {
            $this->pushIncome($data);
        }
    }

    private function startInventory(array $data){
        $totalPrice = round(
            $data['amount'] * $data['unitary_price'],
            2,
            PHP_ROUND_HALF_UP
        );
        $data['total_price'] = $totalPrice;
        $movement = Movement::create($data);
        Balance::create([
            'amount' => $data['amount'],
            'unitary_price' => $data['unitary_price'],
            'total_price' => $totalPrice,
            'movement_id' => $movement->id
        ]);
        $product = Product::find($data['product_id']);
        $product->started_inventory = true;
        $product->save();
    }

    private function pushIncome(array $data){
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
    }
}
