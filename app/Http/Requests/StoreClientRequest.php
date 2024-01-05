<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NotFinalConsumer;

class StoreClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:75', 'unique:App\Models\Person,name', new NotFinalConsumer],
            'phone_number' => ['nullable', 'string', 'size:10'],
            'email'=> ['nullable', 'string', 'max:320'],
            'identification_card' => ['nullable', 'string', 'size:10', 'unique:App\Models\Client,identification_card'],
            'ruc' => ['nullable', 'string', 'size:13', 'unique:App\Models\Client,ruc'],
            'social_reason' => ['nullable', 'string', 'size:75'],
            'address'=> ['nullable', 'string', 'max:200'],
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
            'identification_card' => 'Cedula',
            'ruc' => 'RUC',
            'social_reason' => 'Razón Social',
            'address' => 'Direccón'
        ];
    }
}
