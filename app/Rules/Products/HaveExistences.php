<?php

namespace App\Rules\Products;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;
use App\Models\Warehouse;

class HaveExistences implements ValidationRule, DataAwareRule
{
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

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->validStructure(
            $this->data['products'],
            $this->data['warehouse'],
            $value
        )){
            $warehouse = Warehouse::find($this->data['warehouse']);
            $products = $this->queryProducts(
                $this->data['products']
            );
            $amounts = $value;
            foreach($products as $key => $product){
                $amount = $amounts[$key];
                if(!$product->started_inventory){
                    $fail('El Producto #'. ($key + 1) . ' no tiene el inventario iniciado.');
                } else {
                    $warehousesExistence = $product->warehousesExistences()
                                            ->where('warehouse_id', $warehouse->id)
                                            ->first();
                    if(is_null($warehousesExistence)){
                        $fail('El Producto #'. ($key + 1) . ' no tiene existencias registradas en bodega.');
                    } else {
                        $unitsAvailable = $warehousesExistence->amount;
                        if($unitsAvailable < $amount){
                            $fail('El Producto #'. ($key + 1) . ' no tiene suficientes unidades.');
                        }
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

    private function validStructure(
        mixed $products,
        mixed $warehouse,
        mixed $value
    ): bool
    {
        if(is_array($products) && is_array($value) && is_numeric($warehouse)){
            if(
                (count($products) === count($value))
                && !is_null(Warehouse::find($warehouse))
            ){
                return true;
            }
        }
        return false;
    }
}
