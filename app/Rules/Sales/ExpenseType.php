<?php

namespace App\Rules\Sales;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\MovementCategory;
use App\Models\MovementType;

class ExpenseType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->isExpense($value)){
            $fail('El :attribute seleccionado no es valido.');
        }
    }

    public function isExpense(int $expense_id): bool
    {
        $result = false;
        $expenseTypes = MovementType::where(
            'movement_category_id',
            MovementCategory::expense()->id
        )->get();
        foreach($expenseTypes as $expenseType){
            if($expense_id == $expenseType->id){
                $result = true;
                break;
            }
        }
        return $result;
    }
}
