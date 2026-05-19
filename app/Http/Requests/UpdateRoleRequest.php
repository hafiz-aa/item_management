<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('role.manage');
    }

    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }
}
