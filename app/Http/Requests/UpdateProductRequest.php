<?php

namespace App\Http\Requests;

use App\Rules\UniqueProductTag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');
        return [
            'type' => ['bail', 'required', 'integer', 'exists:types,id'],
            'presentation' => ['bail', 'required', 'integer', 'exists:presentations,id'],
            'name' => [
                'bail', 'required', 'string', 'min:2', 'max:50',
                new UniqueProductTag(ignore: $product->id)
            ],
            'minimun_stock' => ['required', 'integer', 'min:1', 'max:9999'],
            'sale_prices' => ['required', 'array:0,1,2', 'size:3'],
            'sale_prices.*' => ['numeric', 'decimal:0,2', 'min:0.01', 'max:999'],
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
            'type' => 'Tipo',
            'name' => 'Nombre',
            'presentation' => 'Presentación',
            'minimun_stock' => 'Stock Mínimo',
            'sale_prices' => 'Precios de venta',
            'sale_prices.0' => '1 unidad',
            'sale_prices.1' => '6 unidades',
            'sale_prices.2' => '12 unidades'
        ];
    }
}

