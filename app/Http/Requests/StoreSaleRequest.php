<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PastDate;
use App\Rules\SameSize;
use App\Rules\ProductSalePrice;
use App\Rules\Products\HaveExistences;
use App\Rules\Products\StartedInventory;
use App\Rules\ExpenseType;

class StoreSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client' => ['required', 'integer', 'exists:clients,id'],
            'date' => ['required', 'string', 'date_format:Y-m-d', new PastDate],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'integer', 'exists:products,id'],
            'amounts' => ['required', 'array', 'min:1', new SameSize('products', 'Productos'), new HaveExistences],
            'amounts.*' => ['required', 'integer', 'min:1', 'max:65000'],
            'sale_prices' => ['required', 'array', 'min:1', new SameSize('products', 'Productos'), new ProductSalePrice],
            'sale_prices.*' => ['required', 'integer', 'exists:sale_prices,id'],
            'movement_types' => ['required', 'array', 'min:1', new SameSize('products', 'Productos'), new StartedInventory],
            'movement_types.*' => ['required', 'integer', new ExpenseType]
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
            'Client' => 'Cliente',
            'date' => 'Fecha',
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
            'products' => 'Producto',
            'products.*' => 'Producto #:position',
            'sale_prices' => 'Precios Unitarios',
            'sale_prices.*' => 'Precio Unitarios #:position',
            'movement_types' => 'Tipos de Movimiento',
            'movement_types.*' => 'Tipo de Movimiento #:position',
        ];
    }
}
