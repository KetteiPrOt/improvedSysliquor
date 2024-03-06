<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Sales\ProductSalePrice;
use App\Rules\Sales\HaveExistences;
use App\Rules\Sales\ExpenseType;
use App\Rules\Sales\ArrayKeys;
use App\Rules\Sales\ArrayProductKeys;

class StoreSaleRequest extends FormRequest
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
        $maxcreditDate = date(
            'Y-m-d', 
            strtotime($currentDate) + (60 * 60 * 24 * 30)
        );
        $client = $this->get('client');
        $clientRules = is_null($client) ? 'exclude' : ['bail', 'required', 'integer', 'exists:clients,id'];
        return [
            'warehouse' => ['bail', 'required', 'int', 'exists:warehouses,id'],
            'client' => $clientRules,
            'products' => ['bail', 'required', 'array', 'min:1', new ArrayKeys],
            'products.*' => ['bail', 'required', 'integer', 'exists:products,id'],
            'amounts' => ['bail', 'required', 'array', 'min:1', new HaveExistences],
            'amounts.*' => ['bail', 'required', 'integer', 'min:1', 'max:65000'],
            'sale_prices' => ['bail', 'required', 'array', 'min:1', new ProductSalePrice],
            'sale_prices.*' => ['bail', 'required', 'integer', 'exists:sale_prices,id'],
            'movement_types' => ['bail', 'required', 'array', 'min:1'],
            'movement_types.*' => ['bail', 'required', 'integer', new ExpenseType],
            'comments' => ['bail', 'required', 'array'],
            'comments.*' => ['bail', 'string', 'nullable', 'max:750'],
            'credits' => ['bail', 'array', new ArrayProductKeys],
            'credits.*' => ['bail', 'accepted'],
            'due_dates' => ['bail', 'array', new ArrayProductKeys],
            'due_dates.*' => ['bail', 'date', "after:$currentDate", "before_or_equal:$maxcreditDate"]
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
            'amounts' => 'Cantidades',
            'amounts.*' => 'Cantidad #:position',
            'products' => 'Productos',
            'products.*' => 'Producto #:position',
            'sale_prices' => 'Precios Unitarios',
            'sale_prices.*' => 'Precio Unitarios #:position',
            'movement_types' => 'Tipos de Movimiento',
            'movement_types.*' => 'Tipo de Movimiento #:position',
            'comments' => 'Comentarios',
            'comments.*' => 'Comentario #:position',
            'credits' => 'Creditos',
            'credits.*' => 'Credito #:position',
            'due_dates' => 'Fechas de Vencimiento',
            'due_dates.*' => 'Fecha de Vencimiento :position',
        ];
    }

    public function messages(): array
    {
        return [
            'due_dates.*.after' => 'La fecha de vencimiento debe ser mayor al día actual.',
            'due_dates.*.before_or_equal' => 'La fecha de vencimiento no puede ser mayor a 30 días.'
        ];
    }
}
