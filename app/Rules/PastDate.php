<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PastDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isPastDate = date('Y-m-d') >= $value;
        if(!$isPastDate){
            $fail('La fecha no puede ser mayor al ' . date('d/m/Y') . '.');
        }
    }
}
