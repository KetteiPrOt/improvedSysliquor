<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Invoice;

class UniqueInvoiceNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->invoiceNumberIsNull($value)){   
            $number = Invoice::constructInvoiceNumber($value);
            $invoices = Invoice::all();
            foreach($invoices as $invoice){
                if($invoice->number === $number){
                    $fail('El Número de Factura ya ha sido registrado.');
                    break;
                }
            }
        }
    }

    private function invoiceNumberIsNull($value): bool
    {
        $isNull = false;
        if(
            $this->validateInputDataStructure($value)
        ){
            if(
                is_null($value[0])
                && is_null($value[1])
                && is_null($value[2])
            ){
                $isNull = true;
            }
        }
        return $isNull;
    }

    private function validateInputDataStructure($value): bool
    {
        $valid = false;
        if(is_array($value)){
            if(count($value) == 3){
                $valid = true;
            }
        }
        return $valid;
    }
}
