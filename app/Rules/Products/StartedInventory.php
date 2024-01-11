<?php

namespace App\Rules\Products;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;
use App\Models\MovementType;

class StartedInventory implements ValidationRule, DataAwareRule
{
    private int $movementInitialInventory;

    public function __construct()
    {
        $this->movementInitialInventory = MovementType::initialInventory()->id;
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
        if(is_array($value)){
            $movementTypes = $value;
            $products = $this->queryProducts();
            if(count($products) == count($movementTypes)){
                for($i = 0; $i < count($movementTypes); $i++){
                    $product = $products[$i];
                    if($product->started_inventory){
                        if($movementTypes[$i] == $this->movementInitialInventory){
                            $fail('El Producto #'. ($i + 1) . ' ya tiene el inventario iniciado.');
                        }
                    } else {
                        if($movementTypes[$i] != $this->movementInitialInventory){
                            $fail('El Producto #'. ($i + 1) . ' no tiene el inventario iniciado.');
                        }
                    }
                }
            }
        }
    }

    public function queryProducts(){
        $products = [];
        if(is_array($this->data['products'])){
            foreach($this->data['products'] as $productId){
                $product = Product::find($productId);
                if($product){
                    $products[] = $product;
                }
            }
        }
        return $products;
    }
}
