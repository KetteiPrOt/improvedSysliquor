<?php

namespace App\Http\Requests;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->route('role');
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                'regex:/^(?!Administrador$).*/i',
                Rule::unique('roles', 'name')->ignore($role->id)
            ],
            'products' => ['sometimes', 'accepted'],
            'clients' => ['sometimes', 'accepted'],
            'providers' => ['sometimes', 'accepted'],
            'sellers' => ['sometimes', 'accepted'],
            'purchases' => ['sometimes', 'accepted'],
            'sales' => ['sometimes', 'accepted'],
            'kardex' => ['sometimes', 'accepted'],
            'cash-closing' => ['sometimes', 'accepted'],
            'inventory' => ['sometimes', 'accepted'],
            // Only role Administrator can manage permissions
            // 'permissions' => ['sometimes', 'accepted']
        ];
    }

    public function attributes(): array
    {
        $attributes = Permission::$directPermissionNames;
        $attributes['name'] = 'Nombre';
        return $attributes;
    }

    
    public function messages(): array
    {
        return [
            'name.regex' => 'El rol de Administrador es único.'
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator){
                $role = $this->route('role');
                if($role->name === 'Administrador'){
                    $validator->errors()->add(
                        'name',
                        'Dios es siempre True.'
                    );
                }
            }
        ];
    }
}
