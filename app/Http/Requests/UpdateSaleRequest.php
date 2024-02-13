<?php

namespace App\Http\Requests;

use App\Rules\Movements\ProductHaveExistences;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Movements\ProductUnitaryPrice;

class UpdateSaleRequest extends FormRequest
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
        $movement = $this->route('movement');
        return [
            'warehouse' => ['bail', 'required', 'int', 'exists:warehouses,id'],
            'amount' => [
                'bail', 'required', 'integer', 'min:1', 'max:65000',
                new ProductHaveExistences($movement)
            ],
            'sale_price' => [
                'bail', 'required', 'integer', 'exists:sale_prices,id',
                new ProductUnitaryPrice($movement->product)
            ]
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
            'warehouses' => 'Bodega',
            'amount' => 'Cantidad',
            'sale-price' => 'Precio de Venta',
        ];
    }
}
