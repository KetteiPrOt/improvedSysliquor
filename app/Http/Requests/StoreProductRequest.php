<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueProductTag;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'integer', 'exists:types,id'],
            'presentation' => ['required', 'integer', 'exists:presentations,id'],
            'name' => ['required', 'string', 'max:50', new UniqueProductTag],
            'minimun_stock' => ['required', 'integer', 'min:1', 'max:9999'],
            'sale_prices' => ['required', 'array:1,6,12', 'size:3'],
            'sale_prices.*' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:999'],
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
            'sale_prices.1' => '1 unidad',
            'sale_prices.6' => '6 unidades',
            'sale_prices.12' => '12 unidades'
        ];
    }
}
