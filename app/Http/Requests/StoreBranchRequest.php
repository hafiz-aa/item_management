<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('branch.create');
    }

    public function rules(): array
    {
        return [
            'branch_code' => ['required', 'string', 'max:50', 'unique:branches,branch_code'],
            'branch_name' => ['required', 'string', 'max:100'],
            'branch_is_headquarter' => ['nullable', 'boolean'],
            'branch_address' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_code.required' => 'Kode branch wajib diisi.',
            'branch_code.unique' => 'Kode branch sudah digunakan.',
            'branch_name.required' => 'Nama branch wajib diisi.',
        ];
    }
}
