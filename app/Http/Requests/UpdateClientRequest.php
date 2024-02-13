<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
        $client = $this->route('client');
        return [
            'name' => [
                'required', 'string', 'max:75',
                Rule::unique('persons', 'name')->ignore($client->person->id)
            ],
            'phone_number' => ['nullable', 'string', 'size:10'],
            'email'=> ['nullable', 'string', 'max:320'],
            'identification_card' => [
                'nullable', 'string', 'size:10',
                Rule::unique('clients', 'identification_card')->ignore($client->id)
            ],
            'ruc' => [
                'nullable', 'string', 'size:13',
                Rule::unique('clients', 'ruc')->ignore($client->id)
            ],
            'social_reason' => ['nullable', 'string', 'max:75'],
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
