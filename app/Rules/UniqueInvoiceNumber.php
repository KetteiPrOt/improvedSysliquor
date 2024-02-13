<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Invoice;

class UniqueInvoiceNumber implements ValidationRule, DataAwareRule
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
        if(!$this->invoiceNumberIsNull($value)){   
            $number = Invoice::constructInvoiceNumber($value);
            $invoices = Invoice::all();
            foreach($invoices as $invoice){
                if(
                    $invoice->number === $number
                    && $this->data['date'] !== $invoice->date
                ){
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
