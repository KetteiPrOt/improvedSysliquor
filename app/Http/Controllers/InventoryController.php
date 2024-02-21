<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowInventoryRequest;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehousesExistence;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function query()
    {
        return view('inventory.query');
    }

    public function show(ShowInventoryRequest $request)
    {
        $validated = $request->validated();
        $validated['date'] = $validated['date'] ?? date('Y-m-d');
        $orderBy = [
            'column' => $validated['orderBy'] ?? null,
            'order' => ($validated['order'] ?? true) ? 'asc' : 'desc',
        ];
        if(
            isset($validated['warehouse'])
            && ($validated['date'] == date('Y-m-d'))
        ){
            $filters = ['warehouse' => Warehouse::find($validated['warehouse'])];
            $query = $this->filteringByWarehouse($filters, $orderBy);
        } else if(
            !isset($validated['warehouse'])
            && ($validated['date'] == date('Y-m-d'))
        ) {
            $query = $this->withOutFilters($orderBy);
        } else if(
            !isset($validated['warehouse'])
            && ($validated['date'] != date('Y-m-d'))
        ) {
            $filters = ['date' => $validated['date']];
            $query = $this->filteringByPastDate($filters, $orderBy);
        } else {
            $filters = [
                'date' => $validated['date'],
                'warehouse' => Warehouse::find($validated['warehouse'])
            ];
            $query = $this->allFilters($filters, $orderBy);
            dd($query);
        }
        $productsCollection = $query['result'];
        $validated['orderBy'] = $query['column'];
        $total_prices_summation = '$' . number_format(
            $productsCollection->sum('total_price'), 2, ',', ' '
        );
        $products = $this->createPagination($productsCollection, $request, 25);
        $products->withQueryString();
        return view('inventory.show', [
            'products' => $products,
            'total_prices_summation' => $total_prices_summation,
            'filters' => $filters ?? null,
            'orderBy' => [
                'column' => $validated['orderBy'],
                'order' => $validated['order'] ?? true
            ]
        ]);
    }

    private function withOutFilters(array $orderBy): array
    {
        $movementsIds = WarehousesExistence::lastMovements(true);
        return $this->queryWithOutFilters($movementsIds, $orderBy);
    }

    private function filteringByWarehouse(array $filters, array $orderBy): array
    {
        $orderBy['column'] = match($orderBy['column']) {
            'product_name' => 'product_name',
            'amount' => 'amount',
            'total_price' => 'total_price',
            default => 'product_name'
        };
        $query =
            WarehousesExistence::join('products', 'products.id', '=', 'warehouses_existences.product_id')
                ->join('types', 'types.id', '=', 'products.type_id')
                ->join('presentations', 'presentations.id', '=', 'products.presentation_id')
                ->join('movements', 'movements.id', '=', 'warehouses_existences.movement_id')                
                ->join('balances', 'movements.id', '=', 'balances.movement_id')
                ->selectRaw("
                    warehouses_existences.id,
                    CONCAT_WS(' ',
                        `types`.`name`,
                        `products`.`name`,
                        CONCAT(`presentations`.`content`, 'ml')
                    ) as `product_name`,
                    warehouses_existences.amount,
                    balances.unitary_price,
                    (warehouses_existences.amount
                    * balances.unitary_price)
                    as total_price
                ")
                ->where('warehouses_existences.warehouse_id', $filters['warehouse']->id)
                ->orderBy(
                    $orderBy['column'],
                    $orderBy['order']
                );
        return [
            'result' => $query->get(),
            'column' => $orderBy['column']
        ];
    }

    private function filteringByPastDate(array $filters, array $orderBy): array
    {
        $AllPastMovements =
            Product::join('movements', 'movements.product_id', '=', 'products.id')
                ->join('invoices', 'invoices.id', '=', 'movements.invoice_id')
                ->whereRaw("
                    CAST(invoices.created_at AS DATE) <= ?
                ", [$filters['date']])
                ->selectRaw("
                    movements.id as `movement_id`,
                    movements.product_id
                ")
                ->orderBy('movements.product_id')
                ->orderBy('movements.id', 'desc')
                ->get()->toArray();
        
        $movementsIds = [];
        foreach($AllPastMovements as $movement){
            if(isset($movementsIds[$movement['product_id']])){
                continue;
            } else {
                $movementsIds[$movement['product_id']] = $movement['movement_id'];
            }
        }
        return $this->queryWithOutFilters($movementsIds, $orderBy);
    }

    private function allFilters()
    {
        return 'XD';
        // This function is for a next version
    }

    private function queryWithOutFilters(array $movementsIds, array $orderBy): array
    {
        $orderBy['column'] = match($orderBy['column']) {
            'product_name' => 'product_name',
            'status' => 'status',
            'amount' => 'amount',
            'total_price' => 'total_price',
            default => 'product_name'
        };
        $query = Movement::join('products', 'products.id', '=', 'movements.product_id')
                ->join('types', 'types.id', '=', 'products.type_id')
                ->join('presentations', 'presentations.id', '=', 'products.presentation_id')
                ->join('balances', 'movements.id', '=', 'balances.movement_id')
                ->selectRaw("
                    movements.id,
                    CONCAT_WS(' ',
                        `types`.`name`,
                        `products`.`name`,
                        CONCAT(`presentations`.`content`, 'ml')
                    ) as `product_name`,
                    IF(
                        (
                            CAST(balances.amount as SIGNED)
                            - CAST(products.minimun_stock AS SIGNED)
                        ) < 0
                        , \"Bajo\", \"Normal\"
                    )
                    as `status`,
                    products.minimun_stock,
                    balances.amount,
                    balances.unitary_price,
                    balances.total_price
                ")
                ->whereIn('movements.id', $movementsIds)
                ->orderBy(
                    $orderBy['column'],
                    $orderBy['order']
                );
        return [
            'result' => $query->get(),
            'column' => $orderBy['column']
        ];
    }
}
