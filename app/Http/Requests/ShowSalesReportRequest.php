<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ShowSalesReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentDate = date('Y-m-d');
        return [
            'date_from' => ['required', 'date', 'before_or_equal:date_to'],
            'date_to' => ['required', 'date', "before_or_equal:$currentDate"],
            'seller' => ['integer', 'exists:sellers,id'],
            'warehouse' => ['integer', 'exists:warehouses,id'],
            'product' => ['integer', 'exists:products,id'],
            'report_type' => ['required', 'string', 'min:1', 'max:11'],
            'page' => 'integer|min:1',
            'column' => ['string', 'min:1', 'max:50'],
            'order' => ['string', 'min:3', 'max:4']
        ];
    }

    public function attributes(): array
    {
        return [
            'date_from' => 'Fecha Inicial',
            'date_to' => 'Fecha Final',
            'seller' => 'Vendedor',
            'warehouse' => 'Bodega',
            'report_type' => 'Tipo de reporte',
            'column' => 'Columna',
            'order' => 'Orden'
        ];
    }

    public function after(): array
    {
        return [
            function(Validator $validator){
                if(!$this->validOrder()){
                    $this->replace([
                        'order' => 'asc'
                    ]);
                    $validator->errors()->add(
                        'order',
                        'El orden debe ser ascendente o descendente.'
                    );
                }
                if(!$this->validColumn()){
                    $this->replace([
                        'column' => 'id'
                    ]);
                    $validator->errors()->add(
                        'order',
                        'La columna no es válida.'
                    );
                }
            }
        ];
    }

    private function validOrder(): bool
    {
        $this->mergeIfMissing(['order' => 'asc']);
        $valid = match($this->get('order')) {
            'asc' => true,
            'desc' => true,
            default => false
        };
        return $valid;
    }

    private function validColumn(): bool
    {
        $this->mergeIfMissing(['column' => 'id']);
        $valid = match($this->get('column')) {
            'id' => true,
            'date' => true,
            'client_name' => true,
            'product_name' => true,
            'amount' => true,
            'unitary_price' => true,
            'total_price' => true,
            'due_date' => true,
            default => false
        };
        return $valid;
    }
}
