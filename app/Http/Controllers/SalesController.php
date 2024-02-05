<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Movement;
use App\Models\Balance;
use App\Models\SalePrice;

class SalesController extends Controller
{
    public function create(Request $request){
        return view('kardex.sales.create', [
            'clients' => Client::all(),
            'finalConsumer' => Client::finalConsumer(),
            'success' => $request->get('success') ?? null
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
                'invoice_id' => $invoiceId
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
            'person_id' => $person->id
        ]);        
        return $invoice->id;
    }

    private function registerExpense(array $data){
        $lastBalance = Product::find($data['product_id'])
                                ->movements()->orderBy('id', 'desc')->first()->balance;
        $data['unitary_price'] = $lastBalance->unitary_price;
        $movement = Movement::create($data);
        Balance::create([
            'amount' => $lastBalance->amount - $movement->amount,
            'unitary_price' => $lastBalance->unitary_price,
            'movement_id' => $movement->id
        ]);
    }
}
