<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cust_code' => ['required', 'string', 'max:50', 'unique:customer,cust_code'],
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
            'cust_code.required' => 'Kode vendor wajib diisi.',
            'cust_code.unique' => 'Kode vendor sudah digunakan.',
            'cust_name.required' => 'Nama vendor wajib diisi.',
            'cust_status.required' => 'Status wajib dipilih.',
            'custtp_id.required' => 'Tipe vendor wajib dipilih.',
            'custtp_id.exists' => 'Tipe vendor tidak valid.',
        ];
    }
}
