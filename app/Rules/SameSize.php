<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class SameSize implements ValidationRule, DataAwareRule
{ 
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    private string $targetInputKey;

    public string $targetInputName;

    public function __construct($targetInputKey, $targetInputName)
    {
        $this->targetInputKey = $targetInputKey;
        $this->targetInputName = $targetInputName;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $input = $value;
        $targetInput = $this->data[$this->targetInputKey];
        if($this->validStructure($input, $targetInput)){
            $size = count($input);    
            $targetSize = count($targetInput);
            if($size !== $targetSize){
                $fail("Debe iguales :attribute como ".$this->targetInputName.".");
            }
        }
    }

    private function validStructure(mixed $input, mixed $targetInput): bool
    {
        if(is_array($input) && is_array($targetInput)){
            return true;
        } else {
            return false;
        }
    }

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
