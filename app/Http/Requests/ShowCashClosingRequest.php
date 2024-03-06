<?php

namespace App\Http\Requests;

use App\Rules\MinorDate;
use App\Rules\PastDate;
use Illuminate\Foundation\Http\FormRequest;

class ShowCashClosingRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    
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
            'date_from' => [
                'bail',
                'required',
                'string',
                'date_format:Y-m-d',
                new PastDate
            ],
            'date_to' => [
                'bail', 
                'required', 
                'string', 
                'date_format:Y-m-d', 
                new MinorDate
            ],
            'warehouse' => ['bail', 'nullable', 'integer', 'exists:warehouses,id'],
            'seller' => ['bail', 'nullable', 'integer', 'exists:sellers,id'],
            'product' => ['bail', 'nullable', 'integer', 'exists:products,id'],
            'page' => 'integer|min:1'
        ];
    }

    public function attributes(): array
    {
        return [
            'date_from' => 'Fecha Inicial',
            'date_to' => 'Fecha Final',
            'warehouse' => 'Bodega',
            'seller' => 'vendedor',
            'producto' => 'product',
        ];
    }
}
