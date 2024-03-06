<?php

namespace App\Rules\Sales;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ArrayProductKeys implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(count($value) <= $this->data['products']){
            foreach($value as $id => $item){
                if(!in_array($id, $this->data['products'])){
                    $fail('La estructura de los datos no es correcta.');
                }
            }
        } else {
            $fail('La estructura de los datos no es correcta.');
        }
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
