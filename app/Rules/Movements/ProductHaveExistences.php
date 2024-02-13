<?php

namespace App\Rules\Movements;

use App\Models\Movement;
use App\Models\MovementCategory;
use App\Models\Product;
use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ProductHaveExistences implements ValidationRule, DataAwareRule
{
    private Product $product;
    
    private Movement $movement;

    public function __construct(Movement $movement)
    {
        $this->movement = $movement;
        $this->product = $movement->product;    
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
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $unitsAvailable = $this->product->movements()
        //         ->orderBy('id', 'desc')
        //         ->get()?->get(1)?->balance?->amount;
        // if($unitsAvailable < $value){
        //     $fail('El Producto no tiene suficientes unidades.');
        // }

        // -----------------
        $product = $this->product;
        $amount = $value;
        $warehouse = Warehouse::find($this->data['warehouse']);
        if(!$product->started_inventory){
            $fail('El Producto no tiene el inventario iniciado.');
        } else {
            $warehousesExistence = $product->warehousesExistences()
                                    ->where('warehouse_id', $warehouse->id)
                                    ->first();
            if(is_null($warehousesExistence)){
                $fail('El Producto no tiene existencias registradas en bodega.');
            } else {
                $movement = $this->movement;
                $unitsAvailable = $warehousesExistence->amount + $movement->amount;
                if($unitsAvailable < $amount){
                    $fail('El Producto no tiene suficientes unidades.');
                }
            }
        }
    }
}
