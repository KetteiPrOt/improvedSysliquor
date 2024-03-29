<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\MovementCategory;
use App\Models\MovementType;

class IncomeType implements ValidationRule, DataAwareRule
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
        if(!$this->isIncome($value)){
            $fail('El :attribute seleccionado no es valido.');
        }
    }

    public function isIncome(int $income_id): bool
    {
        $result = false;
        $incomeTypes = MovementType::where(
            'movement_category_id',
            MovementCategory::income()->id
        )->get();
        foreach($incomeTypes as $incomeType){
            if($income_id == $incomeType->id){
                $result = true;
                break;
            }
        }
        return $result;
    }
}
