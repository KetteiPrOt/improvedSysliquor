<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PastDate;
use App\Rules\ProductStartedInventory;
use App\Rules\MinorDate;

class ShowKardexRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product' => ['required', 'integer', 'exists:products,id', new ProductStartedInventory],
            'date_to' => ['required', 'string', 'date_format:Y-m-d', new PastDate],
            'date_from' => ['required', 'string', 'date_format:Y-m-d', new PastDate, new MinorDate],
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'product' => 'Producto',
            'date_from' => 'Fecha Inicial',
            'date_to' => 'Fecha Final',
        ];
    }
}
