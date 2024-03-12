<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\MovementCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchasesReportController extends Controller
{
    public function query()
    {
        return view('purchases-report.query');
    }

    public function show(Request $request)
    {
        $currentDate = date('Y-m-d');
        $validator = Validator::make($request->all(), [
            'date_from' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:date_to'],
            'date_to' => ['required', 'date', 'date_format:Y-m-d', "before_or_equal:$currentDate"],
            'only_unpaid' => 'sometimes|accepted',
            'page' => 'integer|min:1'
        ], attributes: [
            'date_from' => 'Fecha Inicial',
            'date_to' => 'Fecha Final',
            'page' => 'Página', 'only_unpaid' => 'Solo sin pagar'
        ]);
 
        if($validator->fails()){
            return
                redirect()->route('purchases-report.query')
                    ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $incomeId = MovementCategory::income()->id;
        $query = Invoice::
            leftJoin('persons', 'persons.id', '=', 'invoices.person_id')
            ->leftJoin('providers', 'persons.id', '=', 'providers.person_id')
            ->selectRaw("
                invoices.id,
                persons.`name` as `provider_name`,
                invoices.number,
                DATE_FORMAT(
                    DATE(invoices.payment_due_date),
                    '%d/%m/%Y'
                ) as `payment_due_date`,
                DATE_FORMAT(
                    DATE(invoices.paid_date),
                    '%d/%m/%Y'
                ) as `paid_date`,
                invoices.comment,
                invoices.paid,
                invoices.created_at
            ")
            ->where('invoices.movement_category_id', $incomeId)
            ->where('invoices.date', '>=', $validated['date_from'])
            ->where('invoices.date', '<=', $validated['date_to']);
        if(isset($validated['only_unpaid'])){
            $query->where('invoices.paid', 'false');
        }
        $invoices = $query->orderBy('created_at')->get();
        $invoices->transform(function($invoice, $key){
            $invoice->total_price = 0;
            foreach($invoice->movements as $movement){
                $invoice->total_price += $movement->total_price;
            }
            $invoice->total_price = '$' . number_format(
                $invoice->total_price, 2, '.', ' '
            );
            return $invoice;
        });
        $invoices = $this->paginate(
            $invoices, 15, $validated['page'] ?? 1, $request->url()
        )->withQueryString();
        return view('purchases-report.show', [
            'invoices' => $invoices,
            'filters' => [
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to']
            ]
        ]);
    }
}
