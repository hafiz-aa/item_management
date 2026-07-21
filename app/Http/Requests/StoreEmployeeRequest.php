<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emp_code' => ['required', 'string', 'max:50', 'unique:employee,emp_code'],
            'emp_name' => ['required', 'string', 'max:100'],
            'emp_sex' => ['nullable', 'string', 'in:0,1'],
            'emp_phone' => ['nullable', 'string', 'max:50'],
            'emp_email' => ['nullable', 'string', 'max:100', 'email'],
            'branch_id' => ['required', 'integer', 'exists:branch,branch_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'emp_code.required' => 'Kode employee wajib diisi.',
            'emp_code.unique' => 'Kode employee sudah digunakan.',
            'emp_name.required' => 'Nama employee wajib diisi.',
            'branch_id.required' => 'Branch wajib dipilih.',
            'branch_id.exists' => 'Branch tidak valid.',
        ];
    }
}
