<?php

namespace App\Rules\Sales;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ArrayKeys implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->validFields($value)){
            $fail('Las estructura de los datos es incorrecta.');
        } else {
            $productsKeys = array_keys($value);
            $normalKeys = $this->normalKeys($productsKeys);
            $amountsKeys = array_keys($this->data['amounts']);
            $salePricesKeys = array_keys($this->data['sale_prices']);
            $movementTypesKeys = array_keys($this->data['movement_types']);
            $commentsKeys = array_keys($this->data['comments']);
            if(
                !$normalKeys
                || $productsKeys != $amountsKeys
                || $productsKeys != $salePricesKeys
                || $productsKeys != $movementTypesKeys
                || $productsKeys != $commentsKeys
            ){
                $fail('Las estructura de los datos es incorrecta.');
            }
        }
    }

    private function validFields($value): bool
    {
        $valid = false;
        if($this->fieldsPresent()){
            if($this->fieldsArray($value)){
                if($this->arraysSameSize($value)){
                    $valid = true;
                }
            }
        }
        return $valid;
    }

    private function fieldsPresent(): bool
    {
        return 
            isset($this->data['amounts'])
            && isset($this->data['sale_prices'])
            && isset($this->data['movement_types'])
            && isset($this->data['comments']);
    }

    private function fieldsArray($value): bool
    {
        return
            is_array($value)
            && is_array($this->data['amounts'])
            && is_array($this->data['sale_prices'])
            && is_array($this->data['movement_types'])
            && is_array($this->data['comments']);
    }

    private function arraysSameSize($value): bool
    {
        return 
            count($value) == count($this->data['amounts'])
            && count($value) == count($this->data['sale_prices'])
            && count($value) == count($this->data['movement_types'])
            && count($value) == count($this->data['comments']);
    }

    private function normalKeys($productKeys): bool
    {
        $valid = true;
        $i = 0;
        foreach($productKeys as $productKey){
            if($productKey != $i){
                $valid = false;
                break;
            }
            $i++;
        }
        return $valid;
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
