<?php

namespace App\Rules\Movements;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ProductUnitaryPrice implements ValidationRule, DataAwareRule
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;    
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
        $valid = false;
        foreach($this->product->salePrices as $salePrice){
            if($salePrice->id == $value){
                if($this->data['amount'] >= $salePrice->unitsNumber->units){
                    $valid = true;
                    break;
                }
            }
        }
        if(!$valid){
            $fail('El precio de venta es invalido.');
        }
    }
}
