<?php

namespace App\Http\Requests;

use App\Rules\Movements\ProductHaveExistences;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Movements\ProductUnitaryPrice;

class UpdateSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $movement = $this->route('movement');
        return [
            'amount' => [
                'required', 'integer', 'min:1', 'max:65000',
                new ProductHaveExistences($movement->product)
            ],
            'sale_price' => [
                'required', 'integer', 'exists:sale_prices,id',
                new ProductUnitaryPrice($movement->product)
            ]
        ];
    }
}
