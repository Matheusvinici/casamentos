<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permission' => ['array'],
            'permission.*' => ['exists:permissions,id'],
        ];
    }
}