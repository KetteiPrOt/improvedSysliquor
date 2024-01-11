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
        $number = '';
        foreach($this->data['invoice_number'] as $key => $part){
            if($key == 2){
                for($i = 1; $i < 10; $i++){
                    if($part < (10**$i)){
                        for($j = 0; $j < (9 - $i); $j++){
                            $number .= '0';
                        }
                        $number .= $part;
                        break;
                    }
                }
                // if($part < 10){
                //     $number .= '00000000'.$part;
                // } else if($part < 100){
                //     $number .= '0000000'.$part;
                // } else if($part < 1000){
                //     $number .= '000000'.$part;
                // } else if($part < 10000){
                //     $number .= '000000'.$part;
                // } else if($part < 100000){
                //     $number .= '00000'.$part;
                // } else if($part < 1000000){
                //     $number .= '0000'.$part;
                // } else if($part < 10000000){
                //     $number .= '000'.$part;
                // } else if($part < 100000000){
                //     $number .= '00'.$part;
                // } else if($part < 1000000000){
                //     $number .= '0'.$part;
                // } else {
                //     $number .= $part;
                // }
            } else {
                if($part < 10){
                    $number .= '00'.$part;
                } else if($part < 100){
                    $number .= '0'.$part;
                } else {
                    $number .= $part;
                }
            }
            $invoices = Invoice::all();
            foreach($invoices as $invoice){
                if($invoice->number === $number){
                    $fail('El Número de Factura ya ha sido registrado.');
                    break;
                }
            }
        }
    }
}
