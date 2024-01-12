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

class PurchaseController extends Controller
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
        $number = '';
        foreach($data['invoice_number'] as $key => $part){
            if($key == 2){
                for($i = 1; $i < 10; $i++){
                    if(intval($part) < (10**$i)){
                        for($j = 0; $j < (9 - $i); $j++){
                            $number .= '0';
                        }
                        $number .= $part;
                        break;
                    }
                }
                // if($part < 10){
                //     $number .= '00000000'.$part;
                // } else if($part < 100){
                //     $number .= '0000000'.$part;
                // } else if($part < 1000){
                //     $number .= '000000'.$part;
                // } else if($part < 10000){
                //     $number .= '000000'.$part;
                // } else if($part < 100000){
                //     $number .= '00000'.$part;
                // } else if($part < 1000000){
                //     $number .= '0000'.$part;
                // } else if($part < 10000000){
                //     $number .= '000'.$part;
                // } else if($part < 100000000){
                //     $number .= '00'.$part;
                // } else if($part < 1000000000){
                //     $number .= '0'.$part;
                // } else {
                //     $number .= $part;
                // }
            } else {
                if(intval($part) < 10){
                    $number .= '00'.$part;
                } else if($part < 100){
                    $number .= '0'.$part;
                } else {
                    $number .= $part;
                }
            }
        }
        $person = Provider::find($data['provider'])->person;
        $invoice = Invoice::create([
            'number' => $number,
            'date' => $data['date'],
            'user_id' => auth()->user()->id,
            'person_id' => $person->id
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
        $movement = Movement::create($data);
        Balance::create([
            'amount' => $data['amount'],
            'unitary_price' => $data['unitary_price'],
            'movement_id' => $movement->id
        ]);
        $product = Product::find($data['product_id']);
        $product->started_inventory = true;
        $product->save();
    }

    private function pushIncome(array $data){
        $product = Product::find($data['product_id']);
        $lastBalance = $product->movements()->orderBy('id', 'desc')->first()->balance;
        $movement = Movement::create($data);
        $newUnitaryPrice = $this->averageWeighted($lastBalance, $movement);
        Balance::create([
            'amount' => $lastBalance->amount + $movement->amount,
            'unitary_price' => $newUnitaryPrice,
            'movement_id' => $movement->id
        ]);    
    }

    private function averageWeighted(Balance $balance, Movement $movement): int | float
    {
        $balanceTotalPrice = $balance->amount * $balance->unitary_price;
        $movementTotalPrice = $movement->amount * $movement->unitary_price;
        $summatoryTotalPrices = $balanceTotalPrice + $movementTotalPrice;
        $sumatoryAmounts = $balance->amount + $movement->amount;
        $unitaryPrice = $summatoryTotalPrices / $sumatoryAmounts;
        return round($unitaryPrice, 2, PHP_ROUND_HALF_UP);
    }
}