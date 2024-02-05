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
