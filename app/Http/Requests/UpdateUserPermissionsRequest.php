<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Permission;

class UpdateUserPermissionsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => ['sometimes', 'accepted'],
            'clients' => ['sometimes', 'accepted'],
            'providers' => ['sometimes', 'accepted'],
            'sellers' => ['sometimes', 'accepted'],
            'purchases' => ['sometimes', 'accepted'],
            'sales' => ['sometimes', 'accepted'],
            'kardex' => ['sometimes', 'accepted'],
            'cash-closing' => ['sometimes', 'accepted'],
            'inventory' => ['sometimes', 'accepted'],
            'sales-report' => ['sometimes', 'accepted'],
            'purchases-report' => ['sometimes', 'accepted'],
            // Only role Administrator can manage permissions
            // 'permissions' => ['sometimes', 'accepted']
        ];
    }

    public function attributes(): array
    {
        return Permission::$directPermissionNames;
    }
}
