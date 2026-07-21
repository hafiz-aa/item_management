<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('customer');

        return [
            'cust_code' => ['required', 'string', 'max:50', Rule::unique('customer', 'cust_code')->ignore($id, 'cust_id')],
            'cust_name' => ['required', 'string', 'max:100'],
            'cust_address' => ['nullable', 'string', 'max:255'],
            'cust_status' => ['required', 'string', 'in:0,1'],
            'cust_phone' => ['nullable', 'string', 'max:50'],
            'cust_fax' => ['nullable', 'string', 'max:50'],
            'cust_email' => ['nullable', 'string', 'max:100', 'email'],
            'custtp_id' => ['required', 'integer', 'exists:customer_type,custtp_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cust_code.required' => 'Kode customer wajib diisi.',
            'cust_code.unique' => 'Kode customer sudah digunakan.',
            'cust_name.required' => 'Nama customer wajib diisi.',
            'cust_status.required' => 'Status wajib dipilih.',
            'custtp_id.required' => 'Tipe customer wajib dipilih.',
            'custtp_id.exists' => 'Tipe customer tidak valid.',
        ];
    }
}
