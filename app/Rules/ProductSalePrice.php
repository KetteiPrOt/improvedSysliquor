<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;

class ProductSalePrice implements ValidationRule, DataAwareRule
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
        $salePrices = $value;
        $products = $this->data['products'];
        $amounts = $this->data['amounts'];
        if(is_array($products) && is_array($amounts) && is_array($salePrices)){
            if((count($products) == count($salePrices)) && (count($products) == count($amounts))){
                for($i = 0; $i < count($products); $i++){
                    $valid = $this->validateSalePrice(
                        $salePrices[$i],
                        $products[$i],
                        $amounts[$i]
                    );
                    if(!$valid){
                        $fail("El Precio Unitario seleccionado no es valido.");
                        break;
                    }
                }
            }
        }
    }

    private function validateSalePrice($salePriceId, $productId, $amount){
        $valid = false;
        $product = Product::find($productId);
        if($product){
            foreach($product->salePrices as $salePrice){
                if($salePrice->id == $salePriceId){
                    if($amount >= $salePrice->unitsNumber->units){
                        $valid = true;
                        break;
                    }
                }
            }
        }
        return $valid;
    }
}
