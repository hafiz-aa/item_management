<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('branch.edit');
    }

    public function rules(): array
    {
        $branchId = $this->route('branch');

        return [
            'branch_code' => ['required', 'string', 'max:50', Rule::unique('branches', 'branch_code')->ignore($branchId, 'branch_id')],
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
