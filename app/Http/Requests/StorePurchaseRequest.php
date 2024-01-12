<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PastDate;
use App\Rules\SameSize;
use App\Rules\IncomeType;
use App\Rules\Products\StartedInventory;
use App\Rules\UniqueInvoiceNumber;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'provider' => ['required', 'integer', 'exists:providers,id'],
            'invoice_number' => ['required', 'array:0,1,2', 'size:3', new UniqueInvoiceNumber],
            'invoice_number.0' => ['required', 'integer', 'min:1', 'max:999'],
            'invoice_number.1' => ['required', 'integer', 'min:1', 'max:999'],
            'invoice_number.2' => ['required', 'integer', 'min:1', 'max:999999999'],
            'date' => ['required', 'string', 'date_format:Y-m-d', new PastDate],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'integer', 'exists:products,id'],
            'amounts' => ['required', 'array', 'min:1', new SameSize('products', 'Productos')],
            'amounts.*' => ['required', 'integer', 'min:1', 'max:65000'],
            'unitary_prices' => ['required', 'array', 'min:1', new SameSize('products', 'Productos')],
            'unitary_prices.*' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:999'],
            'movement_types' => ['required', 'array', 'min:1', new SameSize('products', 'Productos'), new StartedInventory],
            'movement_types.*' => ['required', 'integer', new IncomeType]
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
            'provider' => 'Proveedor',
            'invoice_number' => 'Número de factura',
            'invoice_number.0' => 'Número de factura',
            'invoice_number.1' => 'Número de factura',
            'invoice_number.2' => 'Número de factura',
            'date' => 'Fecha',
            'products' => 'Producto',
            'products.*' => 'Producto #:position',
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
            'unitary_prices' => 'Precios Unitarios',
            'unitary_prices.*' => 'Precio Unitarios #:position',
            'movement_types' => 'Tipos de Movimiento',
            'movement_types.*' => 'Tipo de Movimiento #:position',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.*.integer' => 'Excluye los ceros a la izquierda del número de factura.'
        ];
    }
}
