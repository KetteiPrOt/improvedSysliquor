<?php

namespace App\Rules\Products;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;

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
        $products = $this->queryProducts();
        $amounts = $value;
        if(is_array($products) && is_array($amounts)){
            if((count($products) == count($amounts))){
                for($i = 0; $i < count($products); $i++){
                    $product = $products[$i];
                    $amount = $amounts[$i];
                    if(!$product->started_inventory){
                        $fail('El Producto #'. ($i + 1) . ' no tiene el inventario iniciado.');
                    } else {
                        $unitsAvailable = $product->movements()
                                        ->orderBy('id', 'desc')
                                        ->first()->balance->amount;
                        if($unitsAvailable < $amount){
                            $fail('El Producto #'. ($i + 1) . ' no tiene suficientes unidades.');
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
