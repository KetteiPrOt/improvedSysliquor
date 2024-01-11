<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class SameSize implements ValidationRule, DataAwareRule
{
    private string $targetInputKey;
    public string $targetInputName;

    public function __construct($targetInputKey, $targetInputName)
    {
        $this->targetInputKey = $targetInputKey;
        $this->targetInputName = $targetInputName;
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
            $size = count($value);
            $targetInput = $this->data[$this->targetInputKey];
            if(is_array($targetInput)){
                $targetSize = count($targetInput);
                if($size !== $targetSize){
                    $fail("Debe iguales :attribute como ".$this->targetInputName.".");
                }
            }
        }
    }
}
