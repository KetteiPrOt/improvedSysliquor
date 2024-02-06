<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:75', 'unique:App\Models\Person,name'],
            'phone_number' => ['nullable', 'string', 'size:10'],
            'email'=> ['nullable', 'string', 'max:320'],
            'address'=> ['nullable', 'string', 'max:200'],
            'ruc' => ['required', 'string', 'size:13', 'unique:App\Models\Provider,ruc'],
            'social_reason' => [
                'required', 'string', 'min:2', 'max:75',
                'unique:App\Models\Provider,social_reason'
            ],
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
            'name' => 'Nombre',
            'email' => 'Email',
            'phone_number' => 'Número de Teléfono',
            'ruc' => 'RUC',
            'social_reason' => 'Razón Social',
            'address' => 'Direccón'
        ];
    }
}
