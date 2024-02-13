<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProviderRequest extends FormRequest
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
        $provider = $this->route('provider');
        return [
            'name' => [
                'required', 'string', 'max:75',
                Rule::unique('persons', 'name')->ignore($provider->person->id)
            ],
            'phone_number' => ['nullable', 'string', 'size:10'],
            'email'=> ['nullable', 'string', 'max:320'],
            'address'=> ['nullable', 'string', 'max:200'],
            'ruc' => [
                'required', 'string', 'size:13',
                Rule::unique('providers', 'ruc')->ignore($provider->id)
            ],
            'social_reason' => [
                'required', 'string', 'min:2', 'max:75',
                Rule::unique('providers', 'social_reason')->ignore($provider->id)
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
