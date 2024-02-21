<?php

namespace App\Http\Requests;

use App\Rules\PastDate;
use Illuminate\Foundation\Http\FormRequest;

class ShowInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse' => ['integer', 'exists:warehouses,id'],
            'date' => ['exclude_with:warehouse', 'required', 'string', 'date_format:Y-m-d', new PastDate],
            'orderBy' => ['string', 'min:2', 'max:255'],
            'order' => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse' => 'Bodega',
            'date' => 'Fecha'
        ];
    }
}
