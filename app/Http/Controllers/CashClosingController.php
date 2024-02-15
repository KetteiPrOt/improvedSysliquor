<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowCashClosingRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;
use App\Models\Revenue;
use App\Models\Seller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CashClosingController extends Controller
{
    public function query()
    {
        return view('cash-closing.query');
    }

    public function show(ShowCashClosingRequest $request)
    {
        $validated = $request->validated();
        $query = Revenue::join('movements', 'movements.id', '=', 'revenues.movement_id')
                        ->join('invoices', 'invoices.id', '=', 'movements.invoice_id')
                        ->join('warehouses', 'warehouses.id', '=', 'invoices.warehouse_id')
                        ->join('products', 'products.id', '=', 'movements.product_id')
                        ->join('types', 'types.id', '=', 'products.type_id')
                        ->join('presentations', 'presentations.id', '=', 'products.presentation_id')
                        ->join('users', 'users.id', '=', 'invoices.user_id')
                        ->join('sellers', 'sellers.user_id', '=', 'users.id')
                        ->join('persons', 'persons.id', '=', 'sellers.person_id')
                        ->selectRaw("
                            revenues.id,
                            DATE_FORMAT(
                                DATE(invoices.created_at),
                                '%d/%m/%Y'
                            ) as `date`,
                            warehouses.name as `warehouse_name`,
                            persons.name as `seller_name`,
                            CONCAT_WS(' ',
                                `types`.`name`,
                                `products`.`name`,
                                CONCAT(`presentations`.`content`, 'ml')
                            ) as `product_name`,
                            revenues.amount,
                            revenues.unitary_price,
                            revenues.total_price,
                            revenues.movement_id
                        ")
                        ->whereRaw(
                            'DATE(invoices.created_at) <= ?', [$validated['date_to']]
                        )
                        ->whereRaw(
                            'DATE(invoices.created_at) >= ?', [$validated['date_from']]
                        );
        $query = $this->customizeQuery($query, $validated);
        $revenuesCollection = $query->orderBy('id')->get();
        $total_prices_summation = '$' . number_format(
            $revenuesCollection->sum('total_price'), 2, ',', ''
        );
        $revenues = $this->createPagination($revenuesCollection, $request, 25);
        $revenues->withQueryString();
        if(!$request->has('page')){
            $request->merge(['page' => $revenues->lastPage()]);
            return redirect()->route('cash-closing.show', $request->input());
        } else {
            return view('cash-closing.show', [
                'revenues' => $revenues,
                'filters' => $this->queryFilters($validated),
                'total_prices_summation' => $total_prices_summation
            ]);
        }
    }

    private function createPagination(
        $collection,
        Request $request,
        int $perPage
    ): LengthAwarePaginator
    {
        $currentPage = 1;
        if($request->has('page')){
            if(is_numeric($request->input('page'))){
                if(intval($request->input('page')) > 0){
                    $currentPage = $request->input('page');
                }
            }
        }
        $total = $collection->count();
        $startingPoint = ($currentPage * $perPage) - $perPage;
        $collection = $collection->slice($startingPoint, $perPage);
        return new LengthAwarePaginator(
            $collection,
            $total,
            $perPage,
            options: [
                'path' => $request->url(),
            ]
        );
    }

    private function customizeQuery($query, $validated): object
    {
        if(isset($validated['warehouse'])){
            $query = $query->where('invoices.warehouse_id', $validated['warehouse']);
        }
        if(isset($validated['seller'])){
            $query = $query->where('sellers.id', $validated['seller']);
        }
        if(isset($validated['product'])){
            $query = $query->where('movements.product_id', $validated['product']);
        }
        return $query;
    }

    private function queryFilters(array $validated): array
    {
        return [
            'warehouse' => Warehouse::find($validated['warehouse'] ?? null),
            'seller' => Seller::find($validated['seller'] ?? null),
            'product' => Product::find($validated['product'] ?? null),
            'date_from' => date('d/m/Y', strtotime($validated['date_from'])),
            'date_to' => date('d/m/Y', strtotime($validated['date_to'])),
        ];
    }
}
