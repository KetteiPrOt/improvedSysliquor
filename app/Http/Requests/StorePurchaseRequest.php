<?php

namespace App\Http\Requests;

use App\Models\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PastDate;
use App\Rules\SameSize;
use App\Rules\IncomeType;
use App\Rules\Products\StartedInventory;
use App\Rules\UniqueInvoiceNumber;

class StorePurchaseRequest extends FormRequest
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
        $currentDate = date('Y-m-d');
        return [
            'provider' => ['bail', 'nullable', 'integer', 'exists:providers,id'],
            'date' => ['bail', 'required', 'string', 'date_format:Y-m-d', new PastDate],
            'products' => ['bail', 'required', 'array', 'min:1'],
            'products.*' => ['bail', 'required', 'integer', 'exists:products,id'],
            'amounts' => ['bail', 'required', 'array', 'min:1', new SameSize('products', 'Productos')],
            'amounts.*' => ['bail', 'required', 'integer', 'min:1', 'max:65000'],
            'unitary_prices' => ['bail', 'required', 'array', 'min:1', new SameSize('products', 'Productos')],
            'unitary_prices.*' => ['bail', 'required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:999'],
            'movement_types' => ['bail', 
                'required', 'array', 'min:1', new SameSize('products', 'Productos'), new StartedInventory
            ],
            'movement_types.*' => ['bail', 'required', 'integer', new IncomeType],
            'invoice_number' => [
                'bail', 'nullable', 'array:0,1,2', 'size:3', new UniqueInvoiceNumber
            ],
            'invoice_number.0' => ['bail', 'nullable', 'integer', 'min:1', 'max:999'],
            'invoice_number.1' => ['bail', 'nullable', 'integer', 'min:1', 'max:999'],
            'invoice_number.2' => ['bail', 'nullable', 'integer', 'min:1', 'max:999999999'],
            'warehouse' => 'required|int|exists:warehouses,id',
            'credit_purchase' => 'sometimes|accepted',
            'comment' => 'nullable|string|max:750',
            'payment_due_date' => [
                'required_with:credit_purchase', 'exclude_without:credit_purchase',
                'date', 'date_format:Y-m-d', "after:$currentDate"
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
            'provider' => 'Proveedor',
            'invoice_number' => 'Número de factura',
            'invoice_number.0' => 'Número de factura #:position',
            'invoice_number.1' => 'Número de factura #:position',
            'invoice_number.2' => 'Número de factura #:position',
            'date' => 'Fecha',
            'products' => 'Producto',
            'products.*' => 'Producto #:position',
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
            'unitary_prices' => 'Precios Unitarios',
            'unitary_prices.*' => 'Precio Unitarios #:position',
            'movement_types' => 'Tipos de Movimiento',
            'movement_types.*' => 'Tipo de Movimiento #:position',
            'credit_purchase' => 'Compra a crédito',
            'comment' => 'Comentario'
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.*.integer' => 'Excluye los ceros a la izquierda del número de factura.'
        ];
    }
}
