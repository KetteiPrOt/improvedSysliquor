<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowSalesReportRequest;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function query()
    {
        return view('sales-report.query');
    }

    public function show(ShowSalesReportRequest $request)
    {
        $validated = $request->validated();
        $filters = [
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'report_type' => $validated['report_type'] === 'cashSales' ? 'cashSales' : 'creditSales',
            'warehouse' => $validated['warehouse'] ?? null,
            'product' => $validated['product'] ?? null,
            'seller' => $validated['seller'] ?? null,
        ];
        $query = 
            Movement::join('invoices', 'invoices.id', '=', 'movements.invoice_id')
                ->leftJoin('persons', 'persons.id', '=', 'invoices.person_id')
                ->leftJoin('clients', 'persons.id', '=', 'clients.person_id')
                ->join('products', 'products.id', '=', 'movements.product_id')
                ->join('types', 'types.id', '=', 'products.type_id')
                ->join('presentations', 'presentations.id', '=', 'products.presentation_id')
                ->join('revenues', 'movements.id', '=', 'revenues.movement_id')
                ->join('warehouses', 'invoices.warehouse_id', '=', 'warehouses.id')
                ->join('users', 'users.id', '=', 'invoices.user_id')
                ->join('sellers', 'sellers.user_id', '=', 'users.id')
                ->selectRaw("
                    movements.id,
                    invoices.date AS `unformated_date`,
                    DATE_FORMAT(
                        DATE(invoices.date),
                        '%d/%m/%Y'
                    ) AS `date`,
                    persons.`name` AS `client_name`,
                    CONCAT_WS(' ',
                        `types`.`name`,
                        `products`.`name`,
                        CONCAT(`presentations`.`content`, 'ml')
                    ) as `product_name`,
                    movements.amount,
                    revenues.unitary_price,
                    revenues.total_price,
                    movements.paid,
                    DATE_FORMAT(
                        DATE(movements.due_date),
                        '%d/%m/%Y'
                    ) AS `due_date`,
                    IF(
                        CURRENT_DATE() > movements.due_date, true, false
                    ) AS `expired`,
                    warehouses.name AS `warehouse_name`,
                    users.name AS `seller_name`,
                    movements.comment
                ")
                ->where('invoices.date', '>=', $filters['date_from'])
                ->where('invoices.date', '<=', $filters['date_to'])
                ->where('movements.paid', $filters['report_type'] == 'cashSales' ? true : false);

        if($filters['warehouse']){
            $query->where('warehouses.id', $filters['warehouse']);
        }

        if($filters['product']){
            $query->where('products.id', $filters['product']);
        }

        if($filters['seller']){
            $query->where('sellers.id', $filters['seller']);
        }

        $query->orderBy(
            $validated['column'] ?? 'id',
            $validated['order'] ?? 'asc'
        );

        $filters = [
            'date_from' => date('d/m/Y', strtotime($filters['date_from'])),
            'date_to' => date('d/m/Y', strtotime($filters['date_to'])),
            'report_type' => $filters['report_type'],
            'warehouse' => Warehouse::find($filters['warehouse'])?->name,
            'product' => Product::find($filters['product'])?->productTag(),
            'seller' => Seller::with('person')->find($filters['seller'])?->person->name,
            'column' => $validated['column'] ?? 'id',
            'order' => $validated['order'] ?? 'asc'
        ];

        $sales = $query->get();

        $total_prices_summation = number_format(
            $sales->sum('total_price'), 2, '.', ' '
        );
        
        $sales = $this->paginate(
            $sales, 15, $validated['page'] ?? 1, $request->url()
        )->withQueryString();

        $request->flash();

        return view('sales-report.show', [
            'sales' => $sales,
            'filters' => $filters,
            'total_prices_summation' => $total_prices_summation
        ]);
    }

    public function confirm(Movement $movement)
    {
        if(!$movement->paid){
            $movement->update([
                'paid' => true,
                'due_date' => null
            ]);
        }
        return redirect()->route('sales-report.show')->withInput();
    }
}
