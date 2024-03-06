<?php

namespace App\Rules\Sales;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Product;

class ProductSalePrice implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        for($i = 0; $i < count($value); $i++){
            $validator = $this->validateSalePrice(
                $value[$i],
                $this->data['products'][$i],
                $this->data['amounts'][$i]
            );
            if(!$validator['result']){
                $fail(
                    "El Precio Unitario seleccionado del producto "
                    . $validator['product']
                    ." no es valido."
                );
                break;
            }
        }
    }

    private function validateSalePrice($salePriceId, $productId, $amount): array
    {
        $valid = false;
        $product = Product::with('salePrices')->find($productId);
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
        return [
            'result' => $valid,
            'product' => $valid ? null : $product->productTag()
        ];
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
