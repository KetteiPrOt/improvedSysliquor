<?php

namespace App\Rules\Sales;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;
use App\Models\Warehouse;

class HaveExistences implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $warehouse = Warehouse::find($this->data['warehouse']);
        if($warehouse){
            foreach($this->data['products'] as $key => $productId){
                $product = Product::with('warehousesExistences')->find($productId);
                $amount = $value[$key];
                if($product){
                    if($product->started_inventory){
                        $warehousesExistence = 
                            $product->warehousesExistences()
                                ->where('warehouse_id', $warehouse->id)
                                ->first();
                        if(is_null($warehousesExistence)){
                            $fail(
                                'El Producto '
                                . ($product->productTag())
                                . ' no tiene existencias registradas en bodega.'
                            );
                        } else {
                            $unitsAvailable = $warehousesExistence->amount;
                            if($unitsAvailable < $amount){
                                $fail(
                                    'El Producto '
                                    . ($product->productTag()) 
                                    . ' no tiene suficientes unidades.'
                                );
                            }
                        }
                    } else {
                        $fail(
                            'El Producto '
                            . ($product->productTag())
                            . ' no tiene el inventario iniciado.'
                        );
                    }
                }
            }
        }
    }

    public function queryProducts(array $productIds){
        $products = [];
        foreach($productIds as $productId){
            $product = Product::find($productId);
            if($product){
                $products[] = $product;
            }
        }
        return $products;
    }

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
