<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Product;

class ProductStartedInventory implements ValidationRule
{  
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::find($value);
        if($product){
            if(!$product->started_inventory){
                $fail(
                    'El inventario de este producto aún no ha sido iniciado. Debes iniciarlo o elegir otro.'
                );
            }
        }
    }
}
